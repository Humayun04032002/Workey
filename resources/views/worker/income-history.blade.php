@extends('layouts.worker')

@section('content')
<div class="bg-slate-50 min-h-screen pb-32">
    {{-- স্টিকি হেডার --}}
    <div class="bg-white px-6 pt-12 pb-6 rounded-b-[3rem] shadow-sm border-b border-slate-100 sticky top-0 z-10">
        <div class="flex items-center gap-4">
            <a href="{{ route('worker.profile') }}" class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 active:scale-90 transition-all">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl font-black text-slate-800 tracking-tight">ইনকাম হিস্ট্রি</h1>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">আপনার সফল কাজের তালিকা</p>
            </div>
        </div>
    </div>

    <div class="px-6 mt-8">
        {{-- সামারি কার্ড --}}
        <div class="bg-indigo-600 p-6 rounded-[2.5rem] text-white shadow-xl shadow-indigo-100 mb-8 flex items-center justify-between relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-[10px] text-indigo-100 font-bold uppercase tracking-widest">সর্বমোট উপার্জন</p>
                <h2 class="text-3xl font-black mt-1">৳{{ number_format(auth()->user()->total_earnings ?? 0) }}</h2>
            </div>
            <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm relative z-10">
                <i class="fa-solid fa-chart-line text-2xl"></i>
            </div>
            {{-- Background Decoration --}}
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-500 rounded-full blur-2xl"></div>
        </div>

        <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
            <i class="fa-solid fa-list-ul text-xs"></i>
            লেনদেনের বিবরণ
        </h3>

        <div class="space-y-4">
            @forelse($incomeHistory ?? [] as $history)
                <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between group active:bg-slate-50 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center shadow-inner">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-slate-800 text-sm tracking-tight">{{ $history->job->title ?? 'সম্পন্ন কাজ' }}</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">
                                {{ $history->updated_at->format('d M, Y') }} | {{ $history->updated_at->format('h:i A') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="block text-lg font-black text-emerald-600 tracking-tighter">+৳{{ number_format($history->job->wage ?? 0) }}</span>
                        <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest">Cash Paid</span>
                    </div>
                </div>
            @empty
                <div class="py-20 text-center">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                        <i class="fa-solid fa-receipt text-3xl"></i>
                    </div>
                    <p class="text-slate-400 font-bold text-sm">এখনো কোন ইনকাম রেকর্ড নেই</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection