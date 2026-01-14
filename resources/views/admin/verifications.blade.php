@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-slate-800">পেন্ডিং ভেরিফিকেশন (Identity Verification)</h1>
        <p class="text-slate-500 font-bold text-sm">ওয়ার্কারদের সাবমিট করা তথ্যগুলো যাচাই করে ব্যবস্থা নিন।</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
        @forelse($pending_users as $user)
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
            {{-- ইউজার প্রোফাইল ও এনআইডি নম্বর --}}
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    {{-- ওয়ার্কারের লাইভ ফটো (সেলফি) --}}
                    <div class="relative">
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-indigo-100 shadow-sm">
                        <span class="absolute -bottom-2 -right-2 bg-indigo-600 text-white text-[8px] px-2 py-1 rounded-lg font-black uppercase">Live</span>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-800">{{ $user->name }}</h3>
                        <p class="text-xs font-bold text-slate-400 tracking-tighter">ID: {{ $user->nid_number }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="block text-[10px] font-black text-slate-400 uppercase">ফোন নম্বর</span>
                    <p class="text-xs font-black text-slate-700">{{ $user->phone }}</p>
                </div>
            </div>

            {{-- NID কার্ডের ছবিগুলো --}}
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="relative group">
                    <img src="{{ asset('storage/' . $user->nid_photo_front) }}" class="h-40 w-full rounded-2xl object-cover border border-slate-100 cursor-zoom-in hover:opacity-90 transition-opacity">
                    <span class="absolute top-2 left-2 bg-black/50 text-white text-[8px] font-black px-2 py-1 rounded-md uppercase backdrop-blur-sm">Front Side</span>
                </div>
                <div class="relative group">
                    <img src="{{ asset('storage/' . $user->nid_photo_back) }}" class="h-40 w-full rounded-2xl object-cover border border-slate-100 cursor-zoom-in hover:opacity-90 transition-opacity">
                    <span class="absolute top-2 left-2 bg-black/50 text-white text-[8px] font-black px-2 py-1 rounded-md uppercase backdrop-blur-sm">Back Side</span>
                </div>
            </div>

            {{-- একশন বাটনসমূহ --}}
            <div class="flex gap-3">
                <form action="{{ route('admin.verify.action', [$user->id, 'verified']) }}" method="POST" class="flex-1">
                    @csrf
                    <button class="w-full bg-emerald-500 text-white py-4 rounded-2xl font-black text-xs hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-100 uppercase tracking-widest">
                        Approve User
                    </button>
                </form>
                <button onclick="document.getElementById('reject-form-{{$user->id}}').classList.toggle('hidden')" class="flex-1 bg-slate-50 border border-slate-100 text-rose-500 py-4 rounded-2xl font-black text-xs hover:bg-rose-50 transition-all uppercase tracking-widest">
                    Reject
                </button>
            </div>

            {{-- রিজেক্ট রিজন ফর্ম (হাইড করা থাকে) --}}
            <div id="reject-form-{{$user->id}}" class="mt-4 hidden animate-in slide-in-from-top-2 duration-300 bg-rose-50/50 p-4 rounded-3xl border border-rose-100">
                <form action="{{ route('admin.verify.action', [$user->id, 'rejected']) }}" method="POST">
                    @csrf
                    <label class="block text-[10px] font-black text-rose-400 uppercase mb-2 ml-2">বাতিল করার কারণ</label>
                    <textarea name="reason" placeholder="যেমন: ছবি স্পষ্ট নয় বা তথ্য ভুল..." class="w-full p-4 rounded-2xl bg-white border-none text-xs font-bold focus:ring-rose-500 mb-3" required></textarea>
                    <button class="w-full bg-rose-500 text-white py-3 rounded-xl font-bold text-[10px] uppercase shadow-md">Confirm Rejection</button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center bg-white rounded-[3rem] border border-dashed border-slate-200">
            <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-check-double text-2xl"></i>
            </div>
            <p class="text-slate-400 font-bold italic">বর্তমানে কোন পেন্ডিং ভেরিফিকেশন নেই!</p>
        </div>
        @endforelse
    </div>
</div>
@endsection