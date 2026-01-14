<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the unified login form.
     */
    public function showLogin()
    {
        // যদি ইউজার আগে থেকেই লগইন থাকে, তাকে তার ড্যাশবোর্ডে পাঠিয়ে দাও
        if (Auth::check()) {
            return $this->redirectUserBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    /**
     * Handle the shared login request.
     */
    public function login(Request $request)
    {
        // ১. ইনপুট ভ্যালিডেশন
        $request->validate([
            'phone'    => 'required|numeric',
            'password' => 'required',
        ]);

        // ২. ফোন নাম্বারের সাথে প্রিফিক্স যুক্ত করা
        // ০ দিয়ে শুরু হওয়া নম্বর থেকে ০ বাদ দিয়ে +880 যোগ করা
        $cleanPhone = ltrim($request->phone, '0');
        $fullPhone = '+880' . $cleanPhone;

        // ৩. অথেন্টিকেশন চেষ্টা করা
        $credentials = [
            'phone'    => $fullPhone,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials, $request->remember)) {
            
            $request->session()->regenerate();
            $user = Auth::user();

            // ৪. স্মার্ট রিডাইরেক্ট (রোল অনুযায়ী)
            return $this->redirectUserBasedOnRole($user);
        }

        // ৫. লগইন ব্যর্থ হলে
        return back()->withErrors([
            'phone' => 'প্রদত্ত ফোন নাম্বার বা পাসওয়ার্ডটি সঠিক নয়।',
        ])->onlyInput('phone');
    }

    /**
     * প্রোফাইল বা রোল অনুযায়ী রিডাইরেক্ট লজিক
     */
    protected function redirectUserBasedOnRole($user)
    {
        // match expression ব্যবহার করে ক্লিন রিডাইরেক্ট
        // এখানে route() ব্যবহার করা হয়েছে যাতে ইউআরএল পরিবর্তন হলেও সমস্যা না হয়
        return match($user->role) {
            'admin'    => redirect()->route('admin.dashboard'),
            'employer' => redirect()->route('employer.home'),
            'worker'   => redirect()->route('worker.home'),
            default    => redirect('/'),
        };
    }

    /**
     * লগআউট মেথড
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}