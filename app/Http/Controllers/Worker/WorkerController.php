<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Transaction;
use App\Models\Application;
use App\Models\User;
use App\Models\Setting;
use App\Notifications\JobNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WorkerController extends Controller
{
    /**
     * Worker Dashboard with Nearby Jobs
     */
    public function index(Request $request) 
    {
        $userLat = $request->lat ?? 24.3745;
        $userLng = $request->lng ?? 88.6042;
        $radius = $request->radius ?? 100;

        $nearbyJobs = Job::select('*')
            ->selectRaw(
                "(6371 * acos(cos(radians(?)) * cos(radians(lat)) * cos(radians(lng) - radians(?)) + sin(radians(?)) * sin(radians(lat)))) AS distance",
                [$userLat, $userLng, $userLat]
            )
            ->where('status', 'open')
            ->having('distance', '<=', $radius)
            ->orderBy('distance', 'asc')
            ->get();

        return view('worker.home', compact('nearbyJobs', 'radius'));
    }

    /**
     * নোটিফিকেশন পেজ প্রদর্শন
     */
    public function notifications()
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        return view('worker.notifications', compact('notifications'));
    }

    /**
     * সব নোটিফিকেশন পড়া হয়েছে হিসেবে মার্ক করা
     */
    public function markRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'সব নোটিফিকেশন পড়া হয়েছে।');
    }

    /**
     * ভেরিফিকেশন পেজ প্রদর্শন
     */
    public function showVerifyPage()
    {
        $user = Auth::user();
        return view('worker.verify', compact('user'));
    }

    /**
     * ভেরিফিকেশন সাবমিট করা
     */
    public function submitVerification(Request $request)
    {
        $request->validate([
            'nid_number' => 'required|string|min:10|max:20',
            'nid_front'  => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'nid_back'   => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'live_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('nid_front')) {
            $user->nid_photo_front = $request->file('nid_front')->store('verifications/nid', 'public');
        }
        if ($request->hasFile('nid_back')) {
            $user->nid_photo_back = $request->file('nid_back')->store('verifications/nid', 'public');
        }
        if ($request->hasFile('live_photo')) {
            $user->profile_photo = $request->file('live_photo')->store('verifications/profiles', 'public');
        }

        $user->nid_number = $request->nid_number;
        $user->verification_status = 'pending';
        $user->save();

        $user->notify(new JobNotification([
            'title' => 'ভেরিফিকেশন পেন্ডিং',
            'message' => 'আপনার ভেরিফিকেশন রিকোয়েস্ট জমা হয়েছে। অ্যাডমিন রিভিউ করার পর আপনাকে জানানো হবে।',
            'link' => route('worker.notifications'),
            'type' => 'info'
        ]));

        return redirect()->route('worker.home')->with('success', 'আপনার ভেরিফিকেশন তথ্য সফলভাবে জমা দেওয়া হয়েছে।');
    }

    /**
     * জবে আবেদন
     */
    public function applyJob($id)
    {
        $job = Job::with('employer')->findOrFail($id);
        $user = Auth::user();
        
        if ($user->verification_status !== 'verified') {
            return back()->with('error', 'আবেদন করার আগে আপনার অ্যাকাউন্টটি ভেরিফাই করা বাধ্যতামূলক।');
        }

        $applyFeeSetting = Setting::where('key', 'apply_fee')->first();
        $applyFee = $applyFeeSetting ? (float)$applyFeeSetting->value : 10;

        if ($job->status !== 'open') {
            return back()->with('error', 'দুঃখিত, এই কাজের জন্য নিয়োগ সম্পন্ন হয়ে গেছে।');
        }

        $alreadyApplied = Application::where('job_id', $id)
            ->where('worker_id', $user->id)
            ->exists();

        if ($alreadyApplied) {
            return back()->with('error', 'আপনি ইতিমধ্যে এই জবে আবেদন করেছেন।');
        }

        if ($user->balance < $applyFee) {
            return redirect()->route('worker.wallet')
                ->with('error', 'আবেদন করার জন্য আপনার পর্যাপ্ত ব্যালেন্স নেই। প্রয়োজন: ' . $applyFee . ' টাকা।');
        }

        try {
            DB::transaction(function () use ($user, $job, $applyFee) {
                $user->decrement('balance', $applyFee);

                Transaction::create([
                    'user_id' => $user->id,
                    'amount'  => $applyFee,
                    'type'    => 'debit',
                    'purpose' => 'আবেদন ফি: ' . $job->title,
                    'status'  => 'completed'
                ]);

                Application::create([
                    'job_id'    => $job->id,
                    'worker_id' => $user->id,
                    'status'    => 'pending',
                ]);

                $job->employer->notify(new JobNotification([
                    'title' => 'নতুন আবেদন এসেছে!',
                    'message' => $user->name . ' আপনার "' . $job->title . '" কাজের জন্য আবেদন করেছেন।',
                    'link' => route('employer.applicants'),
                    'type' => 'apply'
                ]));
            });

            return back()->with('success', 'আবেদন সফল হয়েছে।');
        } catch (\Exception $e) {
            return back()->with('error', 'আবেদন করার সময় একটি সমস্যা হয়েছে।');
        }
    }

    /**
     * পেমেন্ট প্রাপ্তি নিশ্চিত করা (এই মেথডটি এরর সমাধান করবে)
     */
    public function confirmPayment($id)
    {
        // নিরাপত্তা নিশ্চিত করতে চেক করা হচ্ছে
        $application = Application::with('job.employer')->where('id', $id)
            ->where('worker_id', Auth::id())
            ->where('status', 'payment_pending')
            ->firstOrFail();

        DB::transaction(function () use ($application) {
            $application->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);

            // নিয়োগকর্তাকে (Employer) নোটিফিকেশন পাঠানো
            try {
                $application->job->employer->notify(new JobNotification([
                    'title' => 'কাজ সফলভাবে সম্পন্ন!',
                    'message' => Auth::user()->name . ' পেমেন্ট বুঝে পেয়েছেন। কাজটির স্ট্যাটাস এখন কমপ্লিট।',
                    'link' => route('employer.home'),
                    'type' => 'success'
                ]));
            } catch (\Exception $e) {}
        });

        return back()->with('success', 'পেমেন্ট প্রাপ্তি নিশ্চিত করা হয়েছে। কাজটি সফলভাবে সম্পন্ন!');
    }

    /**
     * প্রোফাইল পেজ
     */
    // প্রোফাইল দেখার জন্য
public function profile()
{
    $user = Auth::user();
    
    // ভেরিয়েবলটি অবশ্যই এখানে ডিক্লেয়ার থাকতে হবে
    $totalEarnings = \App\Models\Application::join('jobs', 'applications.job_id', '=', 'jobs.id')
        ->where('applications.worker_id', $user->id)
        ->where('applications.status', 'completed')
        ->sum('jobs.wage');

    // আপনার ফোল্ডার স্ট্রাকচার অনুযায়ী ভিউ রিটার্ন
    return view('worker.profile.index', compact('user', 'totalEarnings'));
}

// প্রোফাইল এডিট ফর্মের জন্য
public function editProfile()
{
    $user = Auth::user();
    return view('worker.profile.edit', compact('user'));
}
    /**
     * আবেদনকৃত কাজের তালিকা
     */
    public function applied() 
    {
        $applications = Application::with(['job.employer'])
            ->where('worker_id', Auth::id())
            ->latest()
            ->get();

        return view('worker.applied_jobs', compact('applications'));
    }

    /**
     * কর্মস্থলে পৌঁছালে নোটিফিকেশন পাঠানো
     */
    public function markAsArrived($id)
    {
        $application = Application::with('job.employer')->where('id', $id)
            ->where('worker_id', Auth::id())
            ->firstOrFail();

        if ($application->status !== 'accepted') {
            return back()->with('error', 'মালিক আবেদন গ্রহণ না করা পর্যন্ত এটি করা সম্ভব নয়।');
        }

        $application->update(['arrived_at' => now()]);

        $application->job->employer->notify(new JobNotification([
            'title' => 'কর্মী পৌছেছে',
            'message' => Auth::user()->name . ' কাজের লোকেশনে পৌঁছে গিয়েছেন।',
            'link' => route('employer.home'),
            'type' => 'arrived'
        ]));

        return back()->with('success', 'মালিককে আপনার পৌঁছানোর খবর পাঠানো হয়েছে।');
    }

    /**
     * ওয়ালেট
     */
    public function wallet()
    {
        $history = \App\Models\DepositRequest::where('user_id', auth()->id())
                    ->latest()
                    ->get();

        return view('worker.wallet', compact('history'));
    }

    /**
     * ডিপোজিট রিকোয়েস্ট
     */
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'method' => 'required',
            'sender_number' => 'required',
            'transaction_id' => 'required|unique:deposit_requests,transaction_id',
        ]);

        \App\Models\DepositRequest::create([
            'user_id' => auth()->id(),
            'amount' => $request->amount,
            'method' => $request->method,
            'sender_number' => $request->sender_number,
            'transaction_id' => $request->transaction_id,
            'status' => 'pending',
        ]);

        auth()->user()->notify(new JobNotification([
            'title' => 'রিচার্জ রিকোয়েস্ট সফল',
            'message' => 'আপনার ' . $request->amount . ' টাকার রিচার্জ রিকোয়েস্টটি পেন্ডিং আছে।',
            'link' => route('worker.notifications'),
            'type' => 'wallet'
        ]));

        return back()->with('success', 'আপনার রিকোয়েস্টটি পাঠানো হয়েছে।');
    }

    /**
     * প্রোফাইল আপডেট (Name, Email, Address, Category, Photo)
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // ভ্যালিডেশন: ইমেইল ইউনিক হতে হবে তবে নিজের বর্তমান ইমেইলটি ছাড়া
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:500',
            'category' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // শুধু প্রয়োজনীয় ডেটা নেওয়া হচ্ছে
        $data = $request->only(['name', 'email', 'category', 'address']);

        // প্রোফাইল ফটো হ্যান্ডলিং
        if ($request->hasFile('profile_photo')) {
            // পুরনো ফটো থাকলে ডিলিট করে দেওয়া (Storage ক্লিন রাখার জন্য)
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            
            // নতুন ফটো স্টোর করা
            $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        // ডেটাবেস আপডেট
        $user->update($data);

        // এডিট পেজ থেকে ইনডেক্স পেজে পাঠিয়ে দেওয়া (ভাল ইউজার এক্সপেরিয়েন্সের জন্য)
        // রাউট ফাইলে আমরা প্রোফাইল ইনডেক্স এর নাম দিয়েছি worker.profile.index
return redirect()->route('worker.profile.index')->with('success', 'প্রোফাইল সফলভাবে আপডেট হয়েছে');
    }

    /**
     * কাজের বিস্তারিত
     */
    public function show($id) 
    {
        $job = Job::with('employer')->findOrFail($id);
        return view('worker.job_details', compact('job'));
    }

    /**
     * কাজের তালিকা
     */
    public function jobs() 
    {
        $jobs = Job::where('status', 'open')->latest()->paginate(10);
        return view('worker.jobs_list', compact('jobs'));
    }
    public function incomeHistory()
{
    $user = Auth::user();
    
    // যে কাজগুলো 'completed' হয়েছে সেগুলোর পেমেন্ট ডিটেইলস
    $incomes = \App\Models\Application::with('job')
        ->where('worker_id', $user->id)
        ->where('status', 'completed')
        ->orderBy('updated_at', 'desc')
        ->get();

    return view('worker.profile.income_history', compact('incomes'));
}
public function showReceipt($id)
{
    $income = Application::with(['job', 'job.employer'])
                ->where('worker_id', auth()->id())
                ->findOrFail($id);

    return view('worker.profile.receipt', compact('income'));
}
}