@extends('layouts.admin')

@section('content')
<div class="p-6">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800">রেভিনিউ রিপোর্ট</h1>
            <p class="text-sm text-slate-500 font-medium tracking-wide">আপনার সিস্টেমের মোট আয়ের বিস্তারিত হিসাব</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs font-bold text-slate-400 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100">
                <i class="fa-solid fa-calendar-day mr-1"></i> {{ date('d M, Y') }}
            </span>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        {{-- Total Revenue --}}
        <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-indigo-100 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-indigo-100 text-xs font-bold uppercase tracking-[0.2em] mb-2">সর্বমোট আয়</p>
                <h2 class="text-4xl font-black mb-1">৳{{ number_format($total_revenue) }}</h2>
                <div class="flex items-center gap-2 mt-4 text-xs font-bold bg-white/20 w-fit px-3 py-1.5 rounded-full">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Lifetime Earnings</span>
                </div>
            </div>
            <i class="fa-solid fa-sack-dollar absolute -right-4 -bottom-4 text-9xl text-white/10"></i>
        </div>

        {{-- Today's Revenue --}}
        <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mb-2">আজকের আয়</p>
                <h2 class="text-4xl font-black text-slate-800 mb-1">৳{{ number_format($today_income) }}</h2>
                <div class="flex items-center gap-2 mt-4 text-xs font-bold text-emerald-500 bg-emerald-50 w-fit px-3 py-1.5 rounded-full">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                    <span>Today's Transactions</span>
                </div>
            </div>
            <i class="fa-solid fa-calendar-check absolute -right-4 -bottom-4 text-9xl text-slate-50"></i>
        </div>

        {{-- Transaction Count --}}
        <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-xl shadow-slate-200 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mb-2">মোট ট্রানজেকশন</p>
                <h2 class="text-4xl font-black mb-1">{{ $transactions->total() }}</h2>
                <div class="flex items-center gap-2 mt-4 text-xs font-bold bg-white/10 w-fit px-3 py-1.5 rounded-full">
                    <i class="fa-solid fa-receipt"></i>
                    <span>All Time Orders</span>
                </div>
            </div>
            <i class="fa-solid fa-bolt absolute -right-4 -bottom-4 text-9xl text-white/5"></i>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-50 flex items-center justify-between">
            <h3 class="text-lg font-black text-slate-800">সাম্প্রতিক লেনদেন সমূহ</h3>
            <button class="text-indigo-600 font-bold text-xs bg-indigo-50 px-4 py-2 rounded-xl">
                <i class="fa-solid fa-download mr-1"></i> এক্সেল ডাউনলোড
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase">ইউজার</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase">বিবরণ</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase text-center">টাইপ</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase text-right">পরিমাণ</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase text-right">তারিখ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600 overflow-hidden border border-slate-200">
                                    @if($trx->user->profile_photo)
                                        <img src="{{ asset('storage/'.$trx->user->profile_photo) }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($trx->user->name, 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-800">{{ $trx->user->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold">{{ $trx->user->phone }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-600">{{ $trx->purpose }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-[10px] font-black uppercase px-3 py-1 rounded-lg {{ $trx->type == 'deposit' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                {{ $trx->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="text-sm font-black {{ $trx->type == 'deposit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $trx->type == 'deposit' ? '+' : '-' }} ৳{{ number_format($trx->amount, 2) }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="text-xs font-bold text-slate-500">{{ $trx->created_at->format('d M, h:i A') }}</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-file-invoice text-4xl text-slate-200 mb-2"></i>
                                <p class="text-slate-400 font-bold">কোনো লেনদেন পাওয়া যায়নি</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-6 bg-slate-50/50">
            {{ $transactions->links() }}
        </div>
    </div>
</div>

<style>
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 10s linear infinite;
    }
</style>
@endsection