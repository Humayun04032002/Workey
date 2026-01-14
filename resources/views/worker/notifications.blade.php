@extends('layouts.worker')

@section('content')
<div class="bg-white min-h-screen pb-24">
    {{-- হেডার --}}
    <div class="px-6 pt-8 pb-4 flex items-center gap-4 sticky top-0 bg-white z-40 border-b border-slate-50">
        <a href="{{ route('worker.home') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-50 text-slate-800">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        <h1 class="text-xl font-black text-slate-800 flex-1 text-center mr-10">নোটিফিকেশন</h1>
    </div>

    <div class="px-5 py-6 space-y-4">
        @forelse(auth()->user()->notifications as $notification)
            @php
                $type = $notification->data['type'] ?? 'info';
                $iconData = match($type) {
                    'apply'   => ['fa-briefcase', 'text-blue-500'],
                    'wallet'  => ['fa-money-bill-wave', 'text-emerald-500'],
                    'arrived' => ['fa-clock', 'text-orange-500'],
                    default   => ['fa-bell', 'text-indigo-500'],
                };
            @endphp

            {{-- নোটিফিকেশন কার্ড (ইমেজ অনুযায়ী ডিজাইন) --}}
            <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-start gap-4 {{ $notification->read_at ? 'opacity-60' : '' }}">
                {{-- আইকন বক্স --}}
                <div class="w-12 h-12 flex-shrink-0 bg-slate-50 rounded-2xl flex items-center justify-center {{ $iconData[1] }} text-xl">
                    <i class="fa-solid {{ $iconData[0] }}"></i>
                </div>

                <div class="flex-1 space-y-1">
                    <h3 class="text-[15px] font-black text-slate-800 leading-tight">
                        {{ $notification->data['title'] }}
                    </h3>
                    <p class="text-sm font-bold text-slate-500 leading-snug">
                        {{ $notification->data['message'] }}
                    </p>
                    <p class="text-[11px] font-black text-slate-300 pt-1">
                        {{ $notification->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        @empty
            <div class="text-center py-20">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200 text-3xl">
                    <i class="fa-solid fa-bell-slash"></i>
                </div>
                <p class="text-slate-400 font-bold">কোনো নোটিফিকেশন নেই</p>
            </div>
        @endforelse
    </div>

    {{-- নিচের একশন বাটনগুলো (ইমেজ অনুযায়ী) --}}
    @if(auth()->user()->notifications->count() > 0)
        <div class="px-5 py-4 flex gap-3 fixed bottom-20 left-0 right-0 bg-white/80 backdrop-blur-md">
            <a href="{{ route('markRead') }}" class="flex-1 flex items-center justify-center gap-2 border border-slate-200 py-3.5 rounded-2xl text-xs font-black text-slate-600 active:scale-95 transition-all">
                <i class="fa-solid fa-check-double"></i> সব পড়া হয়েছে
            </a>
            
            {{-- নোট: ডিলিট করার জন্য একটি রাউট থাকা প্রয়োজন --}}
            <form action="#" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 border border-rose-200 py-3.5 rounded-2xl text-xs font-black text-rose-500 active:scale-95 transition-all">
                    <i class="fa-solid fa-trash-can"></i> সব মুছে ফেলুন
                </button>
            </form>
        </div>
    @endif
</div>

<style>
    /* টাইম এবং ডিটেইলস এর জন্য সুন্দর লুক */
    .font-black { font-weight: 900; }
</style>
@endsection