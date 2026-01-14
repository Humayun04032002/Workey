@extends('layouts.employer')

@section('content')
<div class="bg-slate-50 min-h-screen pb-24" style="font-family: 'Hind Siliguri', sans-serif;">
    
    {{-- Header --}}
    <div class="bg-white px-6 pt-10 pb-6 shadow-sm border-b border-slate-100">
        <div class="flex items-center gap-4">
            <a href="{{ route('employer.home') }}" class="text-slate-400">
                <i class="fa-solid fa-arrow-left text-lg"></i>
            </a>
            <h1 class="text-xl font-bold text-slate-800">চলমান কাজসমূহ</h1>
        </div>
    </div>

    <div class="p-4 space-y-4">
        @forelse($jobs as $job)
            {{-- Job Card --}}
            <div class="bg-white rounded-[2rem] p-5 shadow-sm border border-slate-100 relative overflow-hidden">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="bg-emerald-100 text-emerald-600 text-[10px] font-black uppercase px-3 py-1 rounded-full">
                            Status: Filled
                        </span>
                        <h3 class="text-lg font-bold text-slate-800 mt-2">{{ $job->title }}</h3>
                        <p class="text-slate-400 text-xs flex items-center gap-1">
                            <i class="fa-solid fa-calendar-day"></i> 
                            {{ \Carbon\Carbon::parse($job->work_date)->format('d M, Y') }} | {{ $job->start_time }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-emerald-600 font-black text-lg">৳{{ $job->wage }}</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $job->wage_type == 'daily' ? 'দৈনিক' : 'ঘণ্টা প্রতি' }}</p>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-2xl p-4 mb-4">
                    <h4 class="text-xs font-black text-slate-500 uppercase mb-3 tracking-wide">নিযুক্ত কর্মীগণ:</h4>
                    <div class="space-y-3">
                        @foreach($job->applications->where('status', 'accepted') as $app)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-200 overflow-hidden border-2 border-white shadow-sm">
                                        <img src="{{ $app->worker->profile_photo ? asset('storage/'.$app->worker->profile_photo) : 'https://ui-avatars.com/api/?name='.$app->worker->name }}" alt="">
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">{{ $app->worker->name }}</p>
                                        <p class="text-[10px] text-emerald-500 font-bold italic">কাজ করছেন...</p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <a href="tel:{{ $app->worker->phone }}" class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-sm">
                                        <i class="fa-solid fa-phone text-xs"></i>
                                    </a>
                                    {{-- কাজ সম্পন্ন করার বাটন --}}
                                    <form action="{{ route('employer.app.complete', $app->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('আপনি কি নিশ্চিত এই কর্মীর কাজ সম্পন্ন হয়েছে?')" class="bg-slate-800 text-white text-[10px] font-bold px-3 py-2 rounded-xl shadow-md active:scale-95 transition-all">
                                            সম্পন্ন করুন
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-between text-[11px] font-bold text-slate-400 border-t border-dashed border-slate-200 pt-3">
                    <span><i class="fa-solid fa-location-dot mr-1"></i> {{ Str::limit($job->location_name, 30) }}</span>
                    <span class="text-slate-800">মোট কর্মী: {{ $job->worker_count }} জন</span>
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center text-slate-300 mb-4">
                    <i class="fa-solid fa-briefcase text-4xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">বর্তমানে কোনো কাজ চলমান নেই</h3>
                <p class="text-slate-400 text-sm px-10">আপনার পোস্ট করা কাজগুলোতে কর্মী নিয়োগ সম্পন্ন হলে এখানে দেখতে পাবেন।</p>
                <a href="{{ route('employer.job.create') }}" class="mt-6 bg-emerald-600 text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-emerald-100">
                    নতুন কাজ পোস্ট করুন
                </a>
            </div>
        @endforelse
    </div>
</div>

<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
@endsection