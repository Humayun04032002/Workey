<?php
namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller {
    public function apply($id) {
        $worker = Auth::user();
        $job = Job::findOrFail($id);
        $applyFee = 10; // প্রতিটি অ্যাপ্লাইয়ের জন্য ফিক্সড ১০ টাকা

        // ১. ব্যালেন্স চেক
        if ($worker->balance < $applyFee) {
            return redirect()->route('worker.wallet')
                ->with('error', 'আপনার ব্যালেন্স পর্যাপ্ত নয়। দয়া করে রিচার্জ করুন।');
        }

        // ২. ব্যালেন্স কাটা
        $worker->decrement('balance', $applyFee);

        // ৩. অ্যাপ্লিকেশন রেকর্ড (আপনার রিলেশন অনুযায়ী)
        $job->applications()->create([
            'worker_id' => $worker->id,
            'status' => 'pending'
        ]);

        return back()->with('success', 'আবেদন সফল হয়েছে! ১০ টাকা ফি কাটা হয়েছে।');
    }
}