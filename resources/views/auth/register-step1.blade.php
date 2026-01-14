@extends('layouts.auth')

@section('content')
<div class="relative bg-white min-h-[600px] p-6 sm:p-10 rounded-[2.5rem] shadow-2xl shadow-blue-100/50 border border-white/50 overflow-hidden">
    
    <div class="absolute -top-24 -left-24 w-64 h-64 bg-gradient-to-br from-blue-100 to-transparent rounded-full blur-3xl opacity-50 animate-pulse"></div>
    <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-gradient-to-tl from-indigo-100 to-transparent rounded-full blur-3xl opacity-50 animate-pulse" style="animation-delay: 2s;"></div>

    <div class="relative z-10">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-tr from-blue-600 to-blue-800 rounded-2xl mb-4 shadow-xl shadow-blue-200 animate-bounce-slow">
                <span class="text-white text-3xl font-bold italic">W</span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Workey</h1>
            <p class="text-slate-400 font-medium mt-1 text-sm">সঠিক প্রোফাইল বেছে নিয়ে এগিয়ে যান</p>
        </div>

        <form action="{{ route('register.post1') }}" method="POST" class="space-y-5">
            @csrf
            
            <div class="flex items-center justify-center gap-2 mb-6">
                <div class="h-1.5 w-8 rounded-full bg-blue-600"></div>
                <div class="h-1.5 w-4 rounded-full bg-slate-100"></div>
                <div class="h-1.5 w-4 rounded-full bg-slate-100"></div>
            </div>

            <p class="text-[13px] font-bold text-slate-500 uppercase tracking-wider text-center mb-4">অ্যাকাউন্ট টাইপ সিলেক্ট করুন</p>
            
            <div class="grid grid-cols-2 gap-4 mb-8">
                <label class="relative cursor-pointer group">
                    <input type="radio" name="role" value="worker" class="hidden peer" checked onchange="updateUI('worker')">
                    <div class="p-5 border-2 border-slate-50 rounded-3xl text-center bg-slate-50/50 transition-all duration-500 peer-checked:border-blue-600 peer-checked:bg-white peer-checked:shadow-xl peer-checked:shadow-blue-100 group-hover:scale-105 active:scale-95">
                        <div class="text-4xl mb-2 filter grayscale group-hover:grayscale-0 peer-checked:grayscale-0 transition-all animate-float">👷</div>
                        <span class="block font-bold text-slate-700">Worker</span>
                        <div class="absolute top-2 right-2 w-5 h-5 bg-blue-600 rounded-full flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                </label>

                <label class="relative cursor-pointer group">
                    <input type="radio" name="role" value="employer" class="hidden peer" onchange="updateUI('employer')">
                    <div class="p-5 border-2 border-slate-50 rounded-3xl text-center bg-slate-50/50 transition-all duration-500 peer-checked:border-blue-600 peer-checked:bg-white peer-checked:shadow-xl peer-checked:shadow-blue-100 group-hover:scale-105 active:scale-95">
                        <div class="text-4xl mb-2 filter grayscale group-hover:grayscale-0 peer-checked:grayscale-0 transition-all animate-float" style="animation-delay: 1s;">🏢</div>
                        <span class="block font-bold text-slate-700">Employer</span>
                        <div class="absolute top-2 right-2 w-5 h-5 bg-blue-600 rounded-full flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                </label>
            </div>

            <div class="space-y-4">
                <div class="group">
                    <label id="nameLabel" class="block text-slate-700 text-xs font-bold mb-2 ml-1 uppercase tracking-wide">আপনার নাম</label>
                    <input type="text" name="name" id="nameInput" placeholder="আপনার নাম লিখুন" 
                        class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl outline-none focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all font-medium text-slate-700" required>
                </div>

                <div class="group">
                    <label class="block text-slate-700 text-xs font-bold mb-2 ml-1 uppercase tracking-wide">মোবাইল নাম্বার</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none border-r border-slate-200 my-3">
                            <span class="text-slate-500 font-bold pr-3">+880</span>
                        </div>
                        <input type="tel" name="phone" inputmode="numeric" pattern="[0-9]*" maxlength="10" placeholder="XXXXXXXXX" 
                            class="w-full pl-20 pr-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl outline-none focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all font-bold text-slate-700 tracking-widest" required>
                    </div>
                </div>

                <div class="group">
                    <label class="block text-slate-700 text-xs font-bold mb-2 ml-1 uppercase tracking-wide">পাসওয়ার্ড সেট করুন</label>
                    <input type="password" name="password" placeholder="••••••••" 
                        class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl outline-none focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all font-medium" required>
                </div>
            </div>

            <button type="submit" class="w-full mt-6 bg-gradient-to-r from-blue-700 to-indigo-800 text-white py-4 rounded-2xl font-bold text-lg shadow-xl shadow-blue-200 hover:shadow-blue-300 hover:-translate-y-1 transition-all duration-300 active:scale-95 flex items-center justify-center gap-2 group">
                পরবর্তী ধাপ
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </button>
        </form>
    </div>
</div>

<style>
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    .animate-float { animation: float 3s ease-in-out infinite; }
    .animate-bounce-slow { animation: bounce 3s infinite; }
</style>

<script>
    function updateUI(role) {
        const label = document.getElementById('nameLabel');
        const input = document.getElementById('nameInput');
        const isEmployer = role === 'employer';
        
        label.style.opacity = '0';
        setTimeout(() => {
            label.innerText = isEmployer ? 'প্রতিষ্ঠানের নাম (Business Name)' : 'আপনার নাম';
            input.placeholder = isEmployer ? 'আপনার প্রতিষ্ঠানের নাম লিখুন' : 'আপনার নাম লিখুন';
            label.style.opacity = '1';
        }, 150);
    }
</script>
@endsection