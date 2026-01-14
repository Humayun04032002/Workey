<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Application;
use App\Models\User;
use App\Models\Review;
use App\Models\DepositRequest;
use App\Notifications\JobNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployerController extends Controller
{
    /**
     * ড্যাশবোর্ড এবং পরিসংখ্যান
     */
    public function index() 
    {
        $employerId = Auth::id();
        $user = Auth::user();

        $stats = [
            'ongoing_jobs'     => Job::where('employer_id', $employerId)->where('status', 'filled')->count(),
            'total_applicants' => Application::whereHas('job', function($q) use ($employerId) {
                                    $q->where('employer_id', $employerId);
                                })->where('status', 'pending')->count(),
            'completed_jobs'   => Job::where('employer_id', $employerId)->where('status', 'completed')->count(),
            'balance'          => $user->balance ?? 0,
        ];

        return view('employer.home', compact('stats'));
    }

    /**
     * নোটিফিকেশন সিস্টেম (অটো-রিড সহ)
     */
    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->paginate(10);
        $unreadIds = $user->unreadNotifications->pluck('id');

        if ($unreadIds->isNotEmpty()) {
            $user->unreadNotifications()->whereIn('id', $unreadIds)->update(['read_at' => now()]);
        }

        return view('employer.notifications', compact('notifications'));
    }

    public function createJob()
    {
        return view('employer.create_job');
    }

    public function myJobs()
    {
        $myJobs = Job::where('employer_id', Auth::id())
                    ->where('status', '!=', 'completed')
                    ->withCount('applications')
                    ->latest()
                    ->get();
                    
        return view('employer.job_list', compact('myJobs'));
    }

    public function ongoingJobs()
    {
        $employerId = Auth::id();
        $jobs = Job::where('employer_id', $employerId)
                    ->where('status', 'filled')
                    ->with('applications.worker')
                    ->latest()
                    ->get();
        
        return view('employer.jobs.ongoing', compact('jobs'));
    }

    public function jobHistory()
    {
        $employerId = Auth::id();
        $applications = Application::whereHas('job', function($q) use ($employerId) {
                            $q->where('employer_id', $employerId);
                        })
                        ->where('status', 'completed')
                        ->with(['job', 'worker'])
                        ->latest()
                        ->get();

        return view('employer.jobs.history', compact('applications'));
    }

    public function wallet()
    {
        $user = Auth::user();
        $history = DepositRequest::where('user_id', $user->id)->latest()->get();
        return view('employer.wallet', compact('user', 'history'));
    }

    public function storeDeposit(Request $request)
    {
        $request->validate([
            'method' => 'required|string',
            'amount' => 'required|numeric|min:10',
            'sender_number' => 'required|string',
            'transaction_id' => 'required|string|unique:deposit_requests,transaction_id', 
        ]);

        DepositRequest::create([
            'user_id' => Auth::id(),
            'method' => $request->method,
            'amount' => $request->amount,
            'sender_number' => $request->sender_number,
            'transaction_id' => $request->transaction_id,
            'status' => 'pending',
        ]);

        return back()->with('success', 'আপনার রিচার্জ রিকোয়েস্টটি অ্যাডমিনের কাছে পাঠানো হয়েছে।');
    }

    /**
     * নতুন কাজ সংরক্ষণ (FIXED PAYMENT TYPE)
     */
    public function storeJob(Request $request) 
{
    $validated = $request->validate([
        'title'         => 'required|string|max:255',
        'category'      => 'required|string',
        'wage'          => 'required|numeric|min:1',
        'wage_type'     => 'required|in:daily,hourly',
        'work_date'     => 'required|date|after_or_equal:today',
        'start_time'    => 'required',
        'end_time'      => 'required',
        'duration'      => 'required', 
        'worker_count'  => 'required|integer|min:1',
        'location_name' => 'required|string',
        'payment_type'  => 'required|in:cash,in_app',
        'lat'           => 'required|numeric',
        'lng'           => 'required|numeric',
        'description'   => 'nullable|string',
    ]);

    $user = Auth::user();
    
    // মোট বাজেট হিসেব
    $totalBudget = $validated['wage'] * $validated['worker_count'] * (float)$validated['duration'];

    if ($validated['payment_type'] === 'in_app') {
        if ($user->balance < $totalBudget) {
            // AJAX এর জন্য JSON এরর রেসপন্স
            return response()->json([
                'success' => false, 
                'message' => 'আপনার ওয়ালেটে পর্যাপ্ত ব্যালেন্স নেই। প্রয়োজনীয়: ' . $totalBudget . ' টাকা।'
            ], 400);
        }
    }

    try {
        DB::transaction(function () use ($validated, $user, $totalBudget) {
            if ($validated['payment_type'] === 'in_app') {
                $user->decrement('balance', $totalBudget);
            }

            $job = Job::create([
                'employer_id'   => $user->id,
                'title'         => $validated['title'],
                'category'      => $validated['category'],
                'wage'          => $validated['wage'],
                'wage_type'     => $validated['wage_type'],
                'payment_type'  => $validated['payment_type'],
                'work_date'     => $validated['work_date'],
                'start_time'    => $validated['start_time'],
                'end_time'      => $validated['end_time'],
                'duration'      => $validated['duration'], 
                'worker_count'  => $validated['worker_count'],
                'location_name' => $validated['location_name'],
                'lat'           => $validated['lat'],
                'lng'           => $validated['lng'],
                'description'   => $validated['description'],
                'status'        => 'open',
            ]);

            try {
                $user->notify(new JobNotification([
                    'title'   => 'কাজ পোস্ট সফল!',
                    'message' => 'আপনার "' . $job->title . '" কাজটি সফলভাবে পাবলিশ হয়েছে।',
                    'link'    => route('employer.home'),
                    'type'    => 'success'
                ]));
            } catch (\Exception $e) { }
        });

        // সফল হলে JSON রেসপন্স দিন (Redirect নয়)
        return response()->json([
            'success' => true,
            'message' => 'আপনার কাজটি সফলভাবে পাবলিশ হয়েছে!'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'সার্ভারে সমস্যা হয়েছে: ' . $e->getMessage()
        ], 500);
    }
}

    public function applicants()
    {
        $employerId = Auth::id();
        $applications = Application::whereHas('job', function($q) use ($employerId) {
            $q->where('employer_id', $employerId);
        })
        ->with(['job', 'worker']) 
        ->latest()
        ->get();

        return view('employer.applicants', compact('applications'));
    }

    public function viewWorkerProfile($id)
    {
        $worker = User::with(['reviews.employer'])->findOrFail($id);
        return view('employer.worker_profile', compact('worker'));
    }

    public function updateStatus($id, $status)
    {
        if (!in_array($status, ['accepted', 'rejected'])) {
            return back()->with('error', 'অবৈধ অনুরোধ।');
        }

        $application = Application::with(['job', 'worker'])->findOrFail($id);

        if ($application->job->employer_id !== Auth::id()) {
            abort(403);
        }

        DB::transaction(function () use ($application, $status) {
            $application->update(['status' => $status]);

            $notifyTitle = $status === 'accepted' ? 'আবেদন গৃহীত!' : 'আবেদন বাতিল';
            $notifyMsg   = $status === 'accepted' 
                ? 'অভিনন্দন! "' . $application->job->title . '" কাজে আপনার আবেদন গ্রহণ করা হয়েছে।' 
                : 'দুঃখিত, "' . $application->job->title . '" কাজে আপনার আবেদনটি গ্রহণ করা হয়নি।';

            try {
                $application->worker->notify(new JobNotification([
                    'title'   => $notifyTitle,
                    'message' => $notifyMsg,
                    'link'    => '#', 
                    'type'    => $status === 'accepted' ? 'success' : 'danger'
                ]));
            } catch (\Exception $e) { }

            if ($status === 'accepted') {
                $job = $application->job;
                $acceptedCount = Application::where('job_id', $job->id)
                                            ->where('status', 'accepted')
                                            ->count();

                if ($acceptedCount >= $job->worker_count) {
                    $job->update(['status' => 'filled']);
                    Application::where('job_id', $job->id)
                                ->where('status', 'pending')
                                ->update(['status' => 'rejected']);
                }
            }
        });

        return back()->with('success', 'আবেদনটি সফলভাবে আপডেট করা হয়েছে।');
    }

    public function completeJob(Request $request, $id)
    {
        $application = Application::with(['job', 'worker'])->findOrFail($id);
        $job = $application->job;
        
        if ($job->employer_id !== Auth::id()) {
            abort(403);
        }

        return DB::transaction(function () use ($application, $job) {
            
            if ($job->payment_type === 'in_app') {
                $application->worker->increment('balance', $job->wage);
                
                $application->update([
                    'status' => 'completed',
                    'completed_at' => now()
                ]);

                $remaining = Application::where('job_id', $job->id)
                                        ->where('status', '!=', 'completed')
                                        ->count();
                if($remaining == 0) {
                    $job->update(['status' => 'completed']);
                }

                $msg = 'কাজ সম্পন্ন হয়েছে এবং পেমেন্ট শ্রমিকের ওয়ালেটে জমা হয়েছে।';
            } else {
                $application->update([
                    'status' => 'payment_pending'
                ]);
                $msg = 'আপনি পেমেন্ট দিয়েছেন বলে মার্ক করেছেন। শ্রমিক নিশ্চিত করলে এটি সম্পন্ন দেখাবে।';
            }

            try {
                $application->worker->notify(new JobNotification([
                    'title'   => 'পেমেন্ট আপডেট!',
                    'message' => $job->payment_type === 'in_app' 
                                 ? '"' . $job->title . '" কাজের টাকা ওয়ালেটে যোগ হয়েছে।' 
                                 : 'মালিক পেমেন্ট দিয়েছেন বলে জানিয়েছেন। টাকা বুঝে পেলে কনফার্ম করুন।',
                    'link'    => route('worker.applied'), 
                    'type'    => 'info'
                ]));
            } catch (\Exception $e) { }

            return back()->with('success', $msg);
        });
    }

    public function storeReview(Request $request, $id)
    {
        $application = Application::with(['job', 'worker'])->findOrFail($id);

        if ($application->job->employer_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        Review::updateOrCreate(
            [
                'worker_id'   => $application->worker_id,
                'employer_id' => Auth::id(),
                'job_id'      => $application->job_id,
            ],
            [
                'rating'  => $request->rating,
                'comment' => $request->comment ?? 'সফলভাবে কাজটি সম্পন্ন হয়েছে।',
            ]
        );

        return back()->with('success', 'রিভিউ দেওয়ার জন্য ধন্যবাদ!');
    }

    public function profile() 
    { 
        return view('employer.profile', ['user' => Auth::user()]); 
    }

    public function editProfile()
    {
        return view('employer.edit_profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'business_name'   => 'nullable|string',
            'address'         => 'nullable|string',
            'profile_photo'   => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $photoPath = $user->profile_photo;

        if ($request->hasFile('profile_photo')) {
            if ($photoPath) { 
                Storage::disk('public')->delete($photoPath); 
            }
            $photoPath = $request->file('profile_photo')->store('profiles', 'public');
        }

        $user->update([
            'name'           => $validated['name'],
            'business_name'  => $validated['business_name'] ?? $user->business_name,
            'address'        => $validated['address'],
            'profile_photo'  => $photoPath,
        ]);

        return redirect()->route('employer.profile')->with('success', 'প্রোফাইল আপডেট করা হয়েছে!');
    }
}