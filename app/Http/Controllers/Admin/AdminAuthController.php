<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * অ্যাডমিন লগইন পেজ প্রদর্শন করা।
     */
    public function showLogin()
    {
        // যদি ইউজার আগে থেকেই অ্যাডমিন হিসেবে লগইন থাকে, তবে ড্যাশবোর্ডে পাঠিয়ে দাও।
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.login');
    }

    /**
     * লগইন রিকোয়েস্ট হ্যান্ডেল করা।
     */
    public function login(Request $request)
    {
        // ১. ইনপুট ভ্যালিডেশন
        $request->validate([
            'phone'    => 'required|numeric',
            'password' => 'required|string',
        ]);

        // ২. ফোন নম্বর ফরম্যাটিং (+880 যুক্ত করা)
        // ইউজার ০ সহ বা ০ ছাড়া ১০/১১ ডিজিট দিলে তা ক্লিন করে +880 যোগ করা
        $cleanPhone = ltrim($request->phone, '0');
        $fullPhone = '+880' . $cleanPhone;

        $credentials = [
            'phone'    => $fullPhone,
            'password' => $request->password,
        ];

        // ৩. অথেন্টিকেশন চেষ্টা করা
        if (Auth::attempt($credentials, $request->remember)) {
            
            $user = Auth::user();

            // ৪. চেক করা হচ্ছে ইউজারের রোল 'admin' কি না
            if ($user->role === 'admin') {
                
                // সেশন ফিক্সেশন অ্যাটাক রোধে সেশন নতুন করে জেনারেট করা
                $request->session()->regenerate();

                return redirect()->intended(route('admin.dashboard'))
                               ->with('success', 'সফলভাবে অ্যাডমিন প্যানেলে প্রবেশ করেছেন।');
            }

            // যদি ইউজার অ্যাডমিন না হয়, তবে তাকে লগআউট করে এরর দেওয়া
            Auth::logout();
            return redirect()->route('admin.login')->with('error', 'অ্যাক্সেস ডিনাইড! এই প্যানেলটি শুধুমাত্র অ্যাডমিনদের জন্য।');
        }

        // ৫. লগইন তথ্য ভুল হলে
        return back()->with('error', 'প্রদত্ত ফোন নম্বর বা পাসওয়ার্ডটি আমাদের রেকর্ডের সাথে মিলছে না।')->withInput($request->only('phone'));
    }

    /**
     * অ্যাডমিন লগআউট।
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // সেশন সম্পূর্ণ ক্লিয়ার করা
        $request->session()->invalidate();

        // সিএসআরএফ (CSRF) টোকেন রিসেট করা
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'আপনি সফলভাবে লগআউট হয়েছেন।');
    }
}