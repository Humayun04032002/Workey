<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // ১. অ্যাডমিন মিডলওয়্যার অ্যালিয়াস (Alias) রেজিস্টার করা হলো
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // ২. রিডাইরেক্ট লজিক ঠিক করা হলো
        $middleware->redirectTo(
            guests: '/login', // গেস্টদের মেইন লগইন পেজে পাঠানো হবে
            users: function ($request) {
                // ইউজার লগইন থাকা অবস্থায় ভুল করে লগইন লিঙ্কে গেলে রোল অনুযায়ী রিডাইরেক্ট
                $user = auth()->user();
                if ($user && $user->role === 'admin') {
                    return '/admin/dashboard';
                }
                if ($user && $user->role === 'employer') {
                    return '/employer/home';
                }
                return '/worker/home';
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();