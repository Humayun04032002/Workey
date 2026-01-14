<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showStep1() { return view('auth.register-step1'); }

    public function postStep1(Request $request)
    {
        // ফোন নাম্বারের আগে +880 যুক্ত করা এবং বাম দিকের ০ বাদ দেওয়া
        $fullPhone = '+880' . ltrim($request->phone, '0');
        
        // ভ্যালিডেশনের সুবিধার্থে রিকোয়েস্ট ডেটা আপডেট
        $request->merge(['phone' => $fullPhone]);

        $validated = $request->validate([
            'role' => 'required|in:worker,employer',
            'name' => 'required|string|max:255',
            'phone' => 'required|unique:users,phone',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return ($user->role === 'worker') 
            ? redirect()->route('register.worker', $user->id)
            : redirect()->route('register.employer', $user->id);
    }

    public function workerDetails($id) { 
        $user = User::findOrFail($id);
        return view('auth.register-worker', compact('user')); 
    }

    public function employerDetails($id) { 
        $user = User::findOrFail($id);
        return view('auth.register-employer', compact('user')); 
    }

    public function completeRegistration(Request $request)
    {
        // ইউজার খুঁজে বের করা
        $user = User::findOrFail($request->user_id);
        
        // রোল অনুযায়ী ফিল্ড ভ্যালিডেশন
        if ($user->role === 'worker') {
            $data = $request->validate([
                'category' => 'required|string',
                'expected_wage' => 'required|numeric',
            ]);
        } else {
            $data = $request->validate([
                'business_name' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:500',
            ]);
        }

        // ইউজার ডাটা আপডেট (শুধুমাত্র ভ্যালিডেটেড ডাটা আপডেট করা ভালো)
        $user->update($data);

        // অটোমেটিক লগইন সেশন চালু করা
        Auth::login($user);
        $request->session()->regenerate();

        // সঠিক ড্যাশবোর্ডে রিডাইরেক্ট করা
        if ($user->role === 'worker') {
            return redirect()->route('worker.home')->with('success', 'রেজিস্ট্রেশন সফল হয়েছে!');
        }

        if ($user->role === 'employer') {
            return redirect()->route('employer.home')->with('success', 'রেজিস্ট্রেশন সফল হয়েছে!');
        }

        return redirect('/')->with('success', 'রেজিস্ট্রেশন সম্পন্ন হয়েছে!');
    }
}