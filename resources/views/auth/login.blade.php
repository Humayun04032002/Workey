@extends('layouts.auth')

@section('content')
<div class="bg-white min-h-[500px] p-6 sm:p-10 rounded-[2.5rem] shadow-2xl shadow-blue-100/50 border border-gray-50 relative overflow-hidden">
    
    <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-50 rounded-full blur-3xl"></div>
    
    <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-700 rounded-2xl mb-4 shadow-lg shadow-blue-200">
            <span class="text-white text-3xl font-bold italic">W</span>
        </div>
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Workey</h1>
        <p class="text-slate-400 font-medium mt-1">ফিরে আসায় স্বাগতম!</p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-2xl mb-6 text-sm border border-red-100 flex items-center animate-pulse">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="group">
            <label class="block text-slate-700 text-sm mb-2 font-bold ml-1">মোবাইল নাম্বার</label>
            <div class="relative transition-all duration-300">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none border-r border-slate-200 my-3">
                    <span class="text-slate-500 font-bold pr-3">+880</span>
                </div>
                <input type="tel" 
                       name="phone" 
                       pattern="[0-9]*" 
                       inputmode="numeric" 
                       maxlength="10"
                       placeholder="XXXXXXXXX" 
                       oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                       class="w-full pl-20 pr-4 py-4 bg-slate-50 border-2 border-transparent rounded-2xl outline-none focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all text-slate-700 font-bold tracking-widest text-lg" 
                       required>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 ml-1">বাকি ১০টি সংখ্যা ইংরেজিতে লিখুন</p>
        </div>

        <div class="group">
            <div class="flex justify-between items-center mb-2 ml-1">
                <label class="block text-slate-700 text-sm font-bold">পাসওয়ার্ড</label>
                <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition">পিন ভুলে গেছেন?</a>
            </div>
            <div class="relative transition-all duration-300">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-focus-within:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input type="password" name="password" placeholder="••••••••" 
                    class="w-full pl-11 pr-4 py-4 bg-slate-50 border-2 border-transparent rounded-2xl outline-none focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all text-slate-700" required>
            </div>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full bg-blue-700 text-white py-4 rounded-2xl font-bold text-lg shadow-xl shadow-blue-200 hover:bg-blue-800 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                লগইন করুন
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <div class="text-center pt-4">
            <p class="text-slate-500 font-medium">
                অ্যাকাউন্ট নেই? <a href="{{ route('register.step1') }}" class="text-blue-600 font-extrabold hover:text-blue-800 underline underline-offset-8">নতুন অ্যাকাউন্ট খুলুন</a>
            </p>
        </div>
    </form>
</div>
@endsection