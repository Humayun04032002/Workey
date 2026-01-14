<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DepositRequest; // নিশ্চিত করুন এই মডেলটি তৈরি করেছেন

class WalletController extends Controller {
    public function index() {
        $packages = [
            ['id' => 1, 'name' => 'Basic', 'amount' => 50, 'desc' => '৫টি জবে আবেদন'],
            ['id' => 2, 'name' => 'Popular', 'amount' => 100, 'desc' => '১০টি + ১টি ফ্রি আবেদন'],
            ['id' => 3, 'name' => 'Professional', 'amount' => 200, 'desc' => '২২টি জবে আবেদন'],
        ];

        // ইউজারের পেমেন্ট হিস্ট্রি দেখানোর জন্য
        $history = DepositRequest::where('user_id', auth()->id())->latest()->get();

        return view('worker.wallet.index', compact('packages', 'history'));
    }

    public function deposit(Request $request) {
        $request->validate([
            'method' => 'required|in:bkash,nagad,rocket',
            'amount' => 'required|numeric|min:10',
            'sender_number' => 'required|digits:11',
            'transaction_id' => 'required|unique:deposit_requests,transaction_id',
        ], [
            'transaction_id.unique' => 'এই ট্রানজ্যাকশন আইডিটি ইতিমধ্যে ব্যবহার করা হয়েছে।'
        ]);

        // অ্যাডমিন প্যানেলে রিকোয়েস্ট পাঠানো
        DepositRequest::create([
            'user_id' => auth()->id(),
            'method' => $request->method,
            'amount' => $request->amount,
            'sender_number' => $request->sender_number,
            'transaction_id' => $request->transaction_id,
            'status' => 'pending',
        ]);
        
        return back()->with('success', 'আপনার রিকোয়েস্টটি পেন্ডিং আছে। অ্যাডমিন যাচাই করে ব্যালেন্স যোগ করে দেবে।');
    }
    
}