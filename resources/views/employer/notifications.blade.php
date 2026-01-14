@extends('layouts.employer')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="bg-[#FBFBFE] min-h-screen pb-24 animate-fade-in" style="font-family: 'Hind Siliguri', sans-serif;">
    
    {{-- Sticky Header --}}
    <div class="bg-white/80 backdrop-blur-md sticky top-0 z-50 px-6 pt-12 pb-6 border-b border-slate-100/50">
        <div class="flex items-center gap-4">
            <a href="{{ route('employer.home') }}" class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 active:scale-90 transition-transform">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl font-black text-slate-800 tracking-tight">নোটিফিকেশন</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">আপনার সাম্প্রতিক আপডেট</p>
            </div>
        </div>
    </div>

    <div class="p-5 space-y-4">
        @forelse($notifications as $notification)
            @php $data = $notification->data; @endphp
            
            {{-- Notification Item --}}
            <div class="relative group overflow-hidden bg-white p-5 rounded-[2rem] border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.02)] transition-all active:scale-[0.98] 
                {{ $notification->read_at ? 'opacity-60' : 'ring-2 ring-emerald-500/10' }}">
                
                {{-- Unread Indicator (Emerald Dot with Pulse) --}}
                @if(!$notification->read_at)
                    <span class="absolute top-6 right-6 flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.8)]"></span>
                    </span>
                @endif

                <div class="flex gap-4">
                    <div class="w-14 h-14 shrink-0 rounded-2xl flex items-center justify-center 
                        {{ ($data['type'] ?? '') == 'success' ? 'bg-gradient-to-br from-emerald-50 to-emerald-100 text-emerald-600' : 'bg-gradient-to-br from-blue-50 to-blue-100 text-blue-600' }}">
                        <i class="fa-solid {{ ($data['type'] ?? '') == 'success' ? 'fa-circle-check' : 'fa-bell' }} text-xl"></i>
                    </div>

                    <div class="flex-1 space-y-1">
                        <div class="flex justify-between items-start">
                            <h3 class="text-sm font-bold text-slate-800 leading-tight pr-6">
                                {{ $data['title'] ?? 'নতুন আপডেট' }}
                            </h3>
                        </div>
                        
                        <p class="text-[12px] text-slate-500 leading-relaxed font-medium">
                            {{ $data['message'] ?? 'আপনার একটি নতুন নোটিফিকেশন আছে।' }}
                        </p>

                        <div class="flex items-center justify-between pt-2">
                            <span class="text-[10px] font-bold text-slate-300 uppercase tracking-tighter">
                                <i class="fa-regular fa-clock mr-1"></i> {{ $notification->created_at->diffForHumans() }}
                            </span>
                            
                            @if(isset($data['link']) && $data['link'] !== '#')
                                <a href="{{ $data['link'] }}" class="text-[11px] font-black text-emerald-600 flex items-center gap-1 group-hover:gap-2 transition-all">
                                    বিস্তারিত <i class="fa-solid fa-arrow-right-long text-[9px]"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-32 text-center animate-fade-in">
                <div class="relative mb-6">
                    <div class="w-24 h-24 bg-slate-100 rounded-[2.5rem] flex items-center justify-center text-slate-300 rotate-12 transition-transform">
                        <i class="fa-solid fa-bell-slash text-4xl"></i>
                    </div>
                </div>
                <h3 class="text-lg font-black text-slate-800">সব ক্লিয়ার!</h3>
                <p class="text-slate-400 text-xs px-16 mt-2 font-medium">এই মুহূর্তে কোনো নতুন নোটিফিকেশন নেই।</p>
            </div>
        @endforelse

        <div class="mt-8 px-2 custom-pagination">
            {{ $notifications->links() }}
        </div>
    </div>
</div>

<style>
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: slideUp 0.5s ease-out forwards; }
    * { -webkit-tap-highlight-color: transparent; }
    .custom-pagination nav svg { width: 20px; display: inline; }
    .custom-pagination nav div:first-child { display: none; }
</style>
@endsection