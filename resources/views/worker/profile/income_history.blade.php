@extends('layouts.worker')

@section('content')
<div class="bg-slate-50 min-h-screen pb-32">
    {{-- হেডার সেকশন --}}
    <div class="bg-white px-6 pt-12 pb-6 rounded-b-[3rem] shadow-sm mb-6">
        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('worker.profile.index') }}" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-lg font-black text-slate-800">আয়ের ইতিহাস</h2>
            <div class="w-10"></div>
        </div>
        
        {{-- কুইক সামারি ট্যাব --}}
        <div class="flex gap-2 mt-4">
            <div class="flex-1 bg-slate-50 p-3 rounded-2xl border border-slate-100 text-center">
                <p class="text-[9px] uppercase font-bold text-slate-400">মোট সম্পন্ন কাজ</p>
                <p class="text-sm font-black text-slate-700">{{ $incomes->count() }}টি</p>
            </div>
            <div class="flex-1 bg-slate-50 p-3 rounded-2xl border border-slate-100 text-center">
                <p class="text-[9px] uppercase font-bold text-slate-400">মোট উপার্জিত টাকা</p>
                <p class="text-sm font-black text-emerald-600">৳{{ number_format($incomes->sum(fn($i) => $i->job->wage)) }}</p>
            </div>
        </div>
    </div>

    <div class="px-6 space-y-4">
        @forelse($incomes as $income)
            <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-100">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h4 class="font-black text-slate-800 leading-tight">{{ $income->job->title }}</h4>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter mt-1">
                            <i class="fa-regular fa-calendar-check mr-1"></i>
                            {{ $income->updated_at->format('d M, Y | h:i A') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-emerald-600 font-black text-lg">+৳{{ number_format($income->job->wage) }}</span>
                        <div class="flex items-center justify-end gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">সফল</p>
                        </div>
                    </div>
                </div>
                
                {{-- পেমেন্ট মেথড ও রিসিপ্ট বাটন --}}
                <div class="flex items-center justify-between pt-3 border-t border-dashed border-slate-100 mt-2">
                    @php
                        // কন্ডিশন চেক: ইন-অ্যাপ নাকি হ্যান্ড-টু-হ্যান্ড
                        $isInApp = ($income->job->payment_method == 'wallet' || $income->job->payment_type == 'in_app');
                    @endphp

                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg {{ $isInApp ? 'bg-indigo-50 text-indigo-600' : 'bg-orange-50 text-orange-600' }} flex items-center justify-center text-xs shadow-sm">
                            <i class="fa-solid {{ $isInApp ? 'fa-mobile-screen-button' : 'fa-hand-holding-dollar' }}"></i>
                        </div>
                        <p class="text-xs font-bold text-slate-600">পেমেন্ট: 
                            <span class="{{ $isInApp ? 'text-indigo-600' : 'text-orange-600' }}">
                                {{ $isInApp ? 'In-App' : 'Hand to Hand' }}
                            </span>
                        </p>
                    </div>
                    
                    {{-- রিসিপ্ট লিঙ্ক বাটন --}}
                    <a href="{{ route('worker.income.receipt', $income->id) }}" class="text-[10px] font-black text-indigo-600 bg-indigo-50 px-4 py-2 rounded-xl active:scale-95 transition-all flex items-center gap-2">
                        রিসিপ্ট <i class="fa-solid fa-chevron-right text-[8px]"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-24">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-slate-50">
                    <i class="fa-solid fa-receipt text-4xl text-slate-200"></i>
                </div>
                <h3 class="text-slate-800 font-black">কোন রেকর্ড নেই</h3>
                <p class="text-slate-400 text-xs font-bold mt-1">আপনার আয়ের ইতিহাস এখানে দেখা যাবে।</p>
                <a href="{{ route('worker.jobs') }}" class="inline-block mt-6 px-8 py-3 bg-indigo-600 text-white text-xs font-black rounded-2xl shadow-lg shadow-indigo-100">কাজ খুঁজুন</a>
            </div>
        @endforelse
    </div>
</div>
@endsection