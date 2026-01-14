@extends('layouts.worker')
@section('content')
<div class="px-6 pt-8">
    <h2 class="text-2xl font-bold text-slate-800 mb-6">সব কাজ খুঁজুন</h2>
    
    <div class="flex gap-3 overflow-x-auto no-scrollbar mb-8">
        <button class="px-6 py-3 bg-blue-600 text-white rounded-2xl font-bold text-xs whitespace-nowrap">সব ক্যাটাগরি</button>
        <button class="px-6 py-3 bg-white text-slate-500 rounded-2xl font-bold text-xs whitespace-nowrap shadow-sm border border-slate-50">ইলেকট্রিশিয়ান</button>
        <button class="px-6 py-3 bg-white text-slate-500 rounded-2xl font-bold text-xs whitespace-nowrap shadow-sm border border-slate-50">প্লাম্বার</button>
    </div>

    <div class="space-y-4">
        <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-slate-50">
            <div class="flex gap-4 mb-4">
                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-xl">🧱</div>
                <div>
                    <h4 class="font-bold text-slate-800">দেয়াল প্লাস্টার মিস্ত্রি প্রয়োজন</h4>
                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">রাজশাহী সিটি</p>
                </div>
            </div>
            <div class="flex justify-between items-center pt-4 border-t border-slate-50">
                <div class="text-blue-600 font-bold">৳৮০০ <span class="text-slate-300 text-[10px]">/ দিন</span></div>
                <button class="bg-slate-900 text-white px-6 py-2 rounded-xl text-xs font-bold">এপ্লাই করুন</button>
            </div>
        </div>
    </div>
</div>
@endsection