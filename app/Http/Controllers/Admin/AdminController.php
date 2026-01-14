<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Job;
use App\Models\Transaction;
use App\Models\Setting;
use App\Models\DepositRequest;
use App\Models\Report; // রিপোর্ট মডেলটি নিশ্চিত করুন
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ১. ড্যাশবোর্ড (অপরিবর্তিত)
    public function dashboard()
    {
        $stats = [
            'total_workers' => User::where('role', 'worker')->count(),
            'total_employers' => User::where('role', 'employer')->count(),
            'active_jobs' => Job::where('status', 'open')->count(),
            'completed_jobs' => Job::where('status', 'completed')->count(),
            'total_revenue' => Transaction::where('purpose', 'LIKE', '%Apply Fee%')->sum('amount'),
            'today_income' => Transaction::where('purpose', 'LIKE', '%Apply Fee%')->whereDate('created_at', today())->sum('amount'),
            'pending_verifications' => User::where('verification_status', 'pending')->count(),
            'pending_deposits' => DepositRequest::where('status', 'pending')->count(),
        ];

        $recent_activities = User::latest()->take(5)->get();
        $latest_jobs = Job::with('employer')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_activities', 'latest_jobs'));
    }

    // ২. ইউজার ম্যানেজমেন্ট (আপডেটেড: রিপোর্ট ও কাজের সংখ্যা কাউন্ট সহ)
   public function users(Request $request)
{
    // মডেলে থাকা সঠিক রিলেশন নামগুলো ব্যবহার করা হয়েছে
    $query = User::withCount([
        'jobsAsWorker',    // Applications টেবিল থেকে
        'postedJobs',      // Jobs টেবিল থেকে (আপনার মডেলে এই নামেই আছে)
        'reportsReceived'  // Reports টেবিল থেকে
    ]);

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('phone', 'LIKE', "%{$search}%");
        });
    }

    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    $users = $query->latest()->paginate(15)->withQueryString();

    return view('admin.users', compact('users'));
}

    // ২.১ ইউজার ডিটেইলস (নতুন যোগ করা হয়েছে)
    public function showUser($id)
    {
        // ইউজারের কাজের হিস্ট্রি, রিপোর্ট এবং রেটিং লোড করা
        $user = User::with([
            'reportsReceived.reporter', 
            'transactions' => fn($q) => $q->latest()->take(10),
            'reviews' => fn($q) => $q->latest()->take(10)
        ])->withCount(['reportsReceived', 'jobsAsWorker', 'postedJobs'])->findOrFail($id);

        return view('admin.user-details', compact('user'));
    }

    // ২.২ ইউজার ব্যান/সাসপেন্ড (নতুন যোগ করা হয়েছে)
    public function toggleBan($id)
    {
        $user = User::findOrFail($id);
        $user->is_banned = !$user->is_banned; // ডাটাবেসে is_banned (boolean) কলাম থাকতে হবে
        $user->save();

        $status = $user->is_banned ? 'সাসপেন্ড' : 'সচল';
        return back()->with('success', "ইউজার অ্যাকাউন্টটি সফলভাবে {$status} করা হয়েছে।");
    }

    // ৩. ভেরিফিকেশন ম্যানেজমেন্ট (অপরিবর্তিত)
    public function verifications()
    {
        $pending_users = User::where('verification_status', 'pending')->latest()->get();
        return view('admin.verifications', compact('pending_users'));
    }

    public function verifyUser(Request $request, $id, $status)
    {
        $user = User::findOrFail($id);
        $user->verification_status = $status; 
        if ($status == 'rejected') {
            $user->rejection_reason = $request->reason;
        }
        $user->save();
        return back()->with('success', "User verification {$status} successfully.");
    }

    // ৪. পেমেন্ট ম্যানেজমেন্ট (অপরিবর্তিত)
    public function payments()
    {
        $deposits = DepositRequest::with('user')->latest()->paginate(20);
        return view('admin.deposits', compact('deposits'));
    }

    public function handleDeposit(Request $request, $id)
    {
        $deposit = DepositRequest::findOrFail($id);
        
        if ($deposit->status !== 'pending') {
            return back()->with('error', 'এই রিকোয়েস্টটি ইতিমধ্যে প্রসেস করা হয়েছে।');
        }

        if ($request->action == 'approve') {
            DB::transaction(function () use ($deposit) {
                $user = $deposit->user;
                $user->increment('balance', $deposit->amount); 
                Transaction::create([
                    'user_id' => $deposit->user_id,
                    'amount' => $deposit->amount,
                    'type' => 'deposit',
                    'purpose' => 'Wallet Deposit (' . ucfirst($deposit->method) . ')',
                    'status' => 'completed',
                ]);
                $deposit->update(['status' => 'approved']);
            });

            return back()->with('success', 'পেমেন্ট এপ্রুভ করা হয়েছে এবং ব্যালেন্স যোগ হয়েছে।');
        } 

        if ($request->action == 'reject') {
            $request->validate(['admin_note' => 'required'], ['admin_note.required' => 'রিজেক্ট করার কারণ অবশ্যই লিখতে হবে।']);
            $deposit->update([
                'status' => 'rejected',
                'admin_note' => $request->admin_note
            ]);
            return back()->with('error', 'পেমেন্ট রিকোয়েস্ট রিজেক্ট করা হয়েছে।');
        }
    }

    // ৫. জব ম্যানেজমেন্ট
    public function allJobs()
    {
        $jobs = Job::with('employer')->latest()->paginate(20);
        return view('admin.jobs', compact('jobs'));
    }

    public function jobAction($id, $status)
    {
        $job = Job::findOrFail($id);
        $job->status = $status; 
        $job->save();
        return back()->with('success', 'Job status updated successfully.');
    }

    // ৬. সিস্টেম সেটিংস
    public function settings()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->except('_token');
        if (!$request->has('maintenance_mode')) $data['maintenance_mode'] = 0;
        if (!$request->has('registration_enabled')) $data['registration_enabled'] = 0;
        
        foreach ($data as $key => $value) {
            $val = $value ?? '';
            Setting::updateOrCreate(['key' => $key], ['value' => $val]);
        }
        
        return back()->with('success', 'System settings updated successfully!');
    }

    // ৭. রেভিনিউ রিপোর্ট
    public function revenue()
    {
        $total_revenue = Transaction::where('purpose', 'LIKE', '%Apply Fee%')->sum('amount');
        $today_income = Transaction::where('purpose', 'LIKE', '%Apply Fee%')->whereDate('created_at', today())->sum('amount');
        $transactions = Transaction::with('user')->where('purpose', 'LIKE', '%Apply Fee%')->latest()->paginate(20);

        return view('admin.revenue', compact('total_revenue', 'today_income', 'transactions'));
    }
}