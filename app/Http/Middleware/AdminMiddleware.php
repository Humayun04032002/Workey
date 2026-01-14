<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ১. চেক করা ইউজার লগইন আছে কিনা এবং তার রোল 'admin' কিনা
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // ২. যদি অ্যাডমিন না হয় তবে লগআউট করে লগইন পেজে পাঠানো (নিরাপত্তার জন্য)
        Auth::logout();
        
        return redirect()->route('login')->with('error', 'অ্যাক্সেস ডিনাইড! এই পেজে প্রবেশের জন্য আপনার অ্যাডমিন পারমিশন প্রয়োজন।');
    }
}