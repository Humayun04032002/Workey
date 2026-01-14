@extends('layouts.worker')

@section('content')
<div class="bg-slate-50 min-h-screen pb-24">
    <div class="bg-white px-6 py-8 rounded-b-[3rem] shadow-sm">
        <h1 class="text-2xl font-black text-slate-800">সবগুলো কাজ</h1>
        <p class="text-slate-500 text-sm">আপনার এরিয়ার সেরা কাজগুলো খুঁজে নিন</p>
    </div>

    <div class="px-6 -mt-6 space-y-4">
        @forelse($jobs as $job)
            <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-100 flex justify-between items-center group active:scale-95 transition-all">
                <div class="flex gap-4 items-center">
                    <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-xl">
                        💼
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">{{ $job->title }}</h3>
                        <p class="text-xs text-slate-400 font-medium">{{ $job->location_name }}</p>
                        <p class="text-indigo-600 font-bold text-sm mt-1">৳{{ $job->wage }}</p>
                    </div>
                </div>
                <a href="{{ route('worker.show', $job->id) }}" class="bg-slate-900 text-white p-3 rounded-2xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        @empty
            <div class="text-center py-20">
                <p class="text-slate-400 font-medium">বর্তমানে কোনো কাজ নেই</p>
            </div>
        @endforelse
    </div>

    <div class="px-6 mt-6">
        {{ $jobs->links() }}
    </div>
</div>
@endsection