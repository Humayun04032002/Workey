@extends('layouts.employer')

@section('content')
<div class="bg-slate-50 min-h-screen pb-24" style="font-family: 'Hind Siliguri', sans-serif;">
    
    {{-- Header --}}
    <div class="bg-white px-6 pt-10 pb-6 shadow-sm border-b border-slate-100">
        <div class="flex items-center gap-4">
            <a href="{{ route('employer.home') }}" class="text-slate-400">
                <i class="fa-solid fa-arrow-left text-lg"></i>
            </a>
            <h1 class="text-xl font-bold text-slate-800">আমার পোস্টকৃত কাজ</h1>
        </div>
    </div>

    <div class="p-4 space-y-4">
        @php
            // শুধুমাত্র সেই কাজগুলো ফিল্টার করছি যা completed নয়
            $activeJobs = $myJobs->where('status', '!=', 'completed');
        @endphp

        @forelse($activeJobs as $job)
            {{-- Job Card --}}
            <div class="bg-white rounded-[2rem] p-5 shadow-sm border border-slate-100 mb-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $job->status == 'open' ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                            {{ $job->status == 'open' ? 'Active' : 'Filled' }}
                        </span>
                        <h3 class="text-lg font-bold text-slate-800 mt-2">{{ $job->title }}</h3>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-tight">{{ $job->category }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-emerald-600 font-black text-lg">৳{{ number_format($job->wage) }}</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $job->wage_type }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100/50">
                        <p class="text-[10px] text-slate-400 font-bold uppercase">কর্মী প্রয়োজন</p>
                        <p class="text-sm font-black text-slate-700">{{ $job->worker_count }} জন</p>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100/50">
                        <p class="text-[10px] text-slate-400 font-bold uppercase">আবেদন করেছে</p>
                        <p class="text-sm font-black text-emerald-600">{{ $job->applications_count }} জন</p>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-dashed border-slate-100">
                    <div class="flex items-center gap-2 text-slate-400 text-[11px] font-bold">
                        <i class="fa-solid fa-calendar-day"></i>
                        {{ \Carbon\Carbon::parse($job->work_date)->format('d M, Y') }}
                    </div>
                    <div class="flex gap-2">
                        {{-- শুধুমাত্র Active বা Filled কাজের জন্যই আবেদন দেখার সুযোগ থাকবে --}}
                        <a href="{{ route('employer.applicants', ['job_id' => $job->id]) }}" class="bg-slate-800 text-white text-[10px] font-black px-4 py-2 rounded-xl shadow-lg active:scale-95 transition-all">
                            আবেদন দেখুন
                        </a>
                    </div>
                </div>
            </div>
        @empty
            {{-- Empty State (যদি কোনো সক্রিয় কাজ না থাকে) --}}
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center text-slate-300 mb-4">
                    <i class="fa-solid fa-file-circle-plus text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">কোনো সক্রিয় কাজ নেই</h3>
                <p class="text-slate-400 text-sm px-10">আপনার বর্তমান কোনো কাজের বিজ্ঞপ্তি চালু নেই।</p>
                <a href="{{ route('employer.job.create') }}" class="mt-6 bg-emerald-600 text-white px-8 py-3 rounded-2xl font-bold shadow-lg active:scale-95">
                    নতুন কাজ পোস্ট করুন
                </a>
            </div>
        @endforelse
    </div>
</div>

<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&display=swap" rel="stylesheet">
@endsection