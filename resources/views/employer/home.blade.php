@extends('layouts.employer')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="bg-[#FBFBFE] min-h-screen pb-20 animate-fade-in" style="font-family: 'Hind Siliguri', sans-serif;">
    
    {{-- Glassmorphic Header --}}
    <div class="bg-white/80 backdrop-blur-md sticky top-0 z-50 px-6 pt-12 pb-6 border-b border-slate-100/50">
        <div class="flex justify-between items-center">
            <div class="space-y-0.5">
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">{{ Auth::user()->name }}</h1>
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1.5 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                        <span class="text-[11px] font-bold text-emerald-700">ব্যালেন্স: ৳{{ number_format(auth()->user()->balance ?? 0, 0) }}</span>
                    </div>
                </div>
            </div>
            <a href="{{ route('employer.profile') }}" class="relative group">
                <div class="w-12 h-12 rounded-2xl overflow-hidden border-2 border-white shadow-md transition-transform active:scale-90">
                    @if(Auth::user()->profile_photo)
                        <img src="{{ asset('storage/'.Auth::user()->profile_photo) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400">
                            <i class="fa-solid fa-user"></i>
                        </div>
                    @endif
                </div>
            </a>
        </div>
    </div>

    <div class="p-5 space-y-6">
        
        {{-- High-End Stats Grid --}}
        <div class="grid grid-cols-2 gap-4">
            {{-- চলমান কাজ --}}
            <div class="stat-card bg-white p-5 rounded-[2.5rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex flex-col items-center text-center">
                <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center text-white mb-3 shadow-lg shadow-emerald-200/50">
                    <i class="fa-solid fa-briefcase text-xl"></i>
                </div>
                <span class="text-2xl font-black text-slate-800">{{ $stats['ongoing_jobs'] ?? 0 }}</span>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">চলমান কাজ</span>
            </div>

            {{-- নতুন আবেদন --}}
            <div class="stat-card bg-white p-5 rounded-[2.5rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex flex-col items-center text-center">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center text-white mb-3 shadow-lg shadow-blue-200/50">
                    <i class="fa-solid fa-file-signature text-xl"></i>
                </div>
                <span class="text-2xl font-black text-slate-800">{{ $stats['total_applicants'] ?? 0 }}</span>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">নতুন আবেদন</span>
            </div>
        </div>

        {{-- Section Title --}}
        <div class="flex items-center justify-between px-1">
            <h2 class="text-sm font-black text-slate-400 uppercase tracking-widest">কুইক অ্যাকশন</h2>
            <div class="h-[1px] flex-grow ml-4 bg-slate-100"></div>
        </div>

        {{-- Action Buttons List --}}
        <div class="grid grid-cols-2 gap-4">
            @php
                $actions = [
                    ['route' => 'employer.job.create', 'icon' => 'fa-plus', 'title' => 'নতুন কাজ পোস্ট', 'color' => 'emerald'],
                    ['route' => 'employer.job.list', 'icon' => 'fa-list-check', 'title' => 'পোস্টকৃত কাজ', 'color' => 'indigo'],
                    ['route' => 'employer.applicants', 'icon' => 'fa-users-viewfinder', 'title' => 'আবেদনকারী', 'color' => 'orange'],
                    ['route' => 'employer.jobs.ongoing', 'icon' => 'fa-spinner', 'title' => 'চলমান কাজ', 'color' => 'sky'],
                    ['route' => 'employer.jobs.history', 'icon' => 'fa-clock-rotate-left', 'title' => 'পুরানো ইতিহাস', 'color' => 'slate'],
                    ['route' => 'employer.wallet', 'icon' => 'fa-receipt', 'title' => 'পেমেন্ট ও রিচার্জ', 'color' => 'rose'],
                ];
            @endphp

            @foreach($actions as $action)
            <a href="{{ route($action['route']) }}" class="group active:scale-95 transition-all duration-200">
                <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm group-hover:shadow-md transition-shadow flex flex-col items-center text-center gap-3">
                    <div class="w-12 h-12 bg-{{ $action['color'] }}-50 text-{{ $action['color'] }}-600 rounded-full flex items-center justify-center text-lg group-hover:bg-{{ $action['color'] }}-600 group-hover:text-white transition-colors">
                        <i class="fa-solid {{ $action['icon'] }}"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-700">{{ $action['title'] }}</span>
                </div>
            </a>
            @endforeach
        </div>

    </div>
</div>

<style>
    /* অ্যাপ টাইপ অ্যানিমেশন */
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
        animation: slideUp 0.6s ease-out forwards;
    }

    .stat-card {
        transition: transform 0.3s ease;
    }

    .stat-card:active {
        transform: scale(0.95);
    }

    /* হ্যাপটিক ভাইব্রেশন ফিল করার জন্য ট্যাব হাইলাইট রিমুভ */
    * {
        -webkit-tap-highlight-color: transparent;
    }

    body {
        background-color: #FBFBFE;
    }
</style>
@endsection