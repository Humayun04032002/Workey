@extends('layouts.employer')

@section('content')
<div class="bg-slate-50 min-h-screen pb-10">
    <div class="bg-white px-6 pt-12 pb-8 rounded-b-[3rem] shadow-sm text-center relative overflow-hidden">
        <div class="relative inline-block">
            <img src="{{ $worker->profile_photo ? asset('storage/'.$worker->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode($worker->name) }}" 
                 class="w-24 h-24 rounded-[2.5rem] object-cover mx-auto border-4 border-indigo-50 shadow-lg">
            @if($worker->is_verified)
                <div class="absolute -bottom-1 -right-1 bg-blue-500 text-white w-7 h-7 rounded-full border-4 border-white flex items-center justify-center shadow-md">
                    <i class="fa-solid fa-check text-[10px]"></i>
                </div>
            @endif
        </div>
        
        <h1 class="text-2xl font-black text-slate-800 mt-4">{{ $worker->name }}</h1>
        <p class="text-indigo-600 font-bold text-sm tracking-wide">{{ $worker->category ?? 'প্রফেশনাল কর্মী' }}</p>

        <div class="flex justify-center gap-4 mt-8">
            <div class="bg-slate-50 px-5 py-3 rounded-2xl flex-1 max-w-[100px]">
                <p class="text-[9px] font-black text-slate-400 uppercase">রেটিং</p>
                <p class="text-sm font-black text-slate-800">{{ number_format($worker->averageRating(), 1) }}★</p>
            </div>
            <div class="bg-slate-50 px-5 py-3 rounded-2xl flex-1 max-w-[100px]">
                <p class="text-[9px] font-black text-slate-400 uppercase">মজুরি</p>
                <p class="text-sm font-black text-slate-800">৳{{ $worker->expected_wage ?? 'আলোচনা সাপেক্ষে' }}</p>
            </div>
            <div class="bg-slate-50 px-5 py-3 rounded-2xl flex-1 max-w-[100px]">
                <p class="text-[9px] font-black text-slate-400 uppercase">শহর</p>
                <p class="text-sm font-black text-slate-800 truncate">{{ Str::limit($worker->address, 6) }}</p>
            </div>
        </div>
    </div>

    <div class="px-6 mt-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-black text-slate-800">মালিকদের মতামত</h2>
            <span class="text-xs font-bold text-slate-400 bg-white px-3 py-1 rounded-full shadow-sm">{{ $worker->reviews->count() }} রিভিউ</span>
        </div>

        @forelse($worker->reviews as $review)
            <div class="bg-white p-5 rounded-[1.5rem] shadow-sm mb-4 border border-slate-100">
                <div class="flex items-center gap-3 mb-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($review->employer->name) }}&background=random" class="w-8 h-8 rounded-full">
                    <div>
                        <p class="text-xs font-black text-slate-700">{{ $review->employer->name }}</p>
                        <div class="flex text-amber-400 text-[8px] mt-0.5">
                            @for($i=1; $i<=5; $i++)
                                <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                            @endfor
                        </div>
                    </div>
                </div>
                <p class="text-slate-500 text-xs leading-relaxed italic">"{{ $review->comment }}"</p>
            </div>
        @empty
            <div class="text-center py-10 bg-white rounded-3xl border-2 border-dashed border-slate-100">
                <p class="text-slate-400 text-sm font-bold">এখনো কোনো রিভিউ নেই।</p>
            </div>
        @endforelse
    </div>
    
    <div class="fixed bottom-6 left-6 right-6">
        <a href="tel:{{ $worker->phone }}" class="flex items-center justify-center gap-3 bg-emerald-500 text-white py-4 rounded-2xl font-black shadow-lg shadow-emerald-200 active:scale-95 transition-all">
            <i class="fa-solid fa-phone"></i> সরাসরি কল করুন
        </a>
    </div>
</div>
@endsection