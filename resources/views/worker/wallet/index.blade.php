@extends('layouts.worker')

@section('content')
<div class="px-6 pt-8 max-w-lg mx-auto pb-24">
    <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-xl mb-8 relative overflow-hidden">
        <div class="relative z-10">
            <p class="text-indigo-100 text-xs font-bold uppercase tracking-widest opacity-80">ওয়ালেট ব্যালেন্স</p>
            <h2 class="text-4xl font-black mt-2">৳{{ number_format(auth()->user()->balance, 2) }}</h2>
            <p class="mt-4 text-[10px] bg-white/20 w-fit px-3 py-1 rounded-full backdrop-blur-md font-bold">
                প্রতি আবেদনে ফি ১০ টাকা
            </p>
        </div>
        <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full"></div>
    </div>

    <h3 class="text-lg font-black text-slate-800 mb-5 flex items-center gap-2">
        <span class="w-1.5 h-6 bg-indigo-600 rounded-full"></span> ব্যালেন্স কিনুন
    </h3>

    <div class="grid grid-cols-1 gap-4">
        @foreach($packages as $package)
        <div class="bg-white p-6 rounded-3xl border border-slate-100 flex justify-between items-center group hover:border-indigo-600 transition-all">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase">{{ $package['name'] }}</p>
                <h4 class="text-xl font-black text-slate-800">৳{{ $package['amount'] }}</h4>
                <p class="text-[10px] text-slate-500 font-bold">{{ $package['desc'] }}</p>
            </div>
            <form action="{{ route('worker.deposit') }}" method="POST">
                @csrf
                <input type="hidden" name="amount" value="{{ $package['amount'] }}">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl text-xs font-black shadow-lg shadow-indigo-100 group-hover:bg-slate-900 transition-colors">
                    কিনুন
                </button>
            </form>
        </div>
        @endforeach
    </div>
</div>
@endsection