@extends('layouts.worker')

@section('content')
<div class="bg-slate-50 min-h-screen pb-24">
    {{-- মেসেজ অ্যালার্ট --}}
    @if(session('success'))
        <div class="fixed top-4 left-6 right-6 z-50 bg-emerald-500 text-white p-4 rounded-2xl font-bold text-sm shadow-xl flex items-center gap-3 animate-bounce-short">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="fixed top-4 left-6 right-6 z-50 bg-rose-500 text-white p-4 rounded-2xl font-bold text-sm shadow-xl flex items-center gap-3 animate-bounce-short">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- হেডার সেকশন --}}
    <div class="bg-white px-6 pt-8 pb-4 shadow-sm sticky top-0 z-40">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('worker.home') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-50 text-slate-800">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
            <h1 class="text-xl font-black text-slate-800 flex-1 text-center mr-10">আবেদনের অবস্থা</h1>
        </div>

        {{-- ট্যাব মেনু --}}
        <div class="flex items-center justify-between border-b border-slate-100 overflow-x-auto no-scrollbar gap-6">
            <button onclick="filterStatus('all', event)" class="tab-btn active px-2 pb-3 text-sm font-bold whitespace-nowrap transition-all border-b-2 border-indigo-600 text-indigo-600">সকল</button>
            <button onclick="filterStatus('pending', event)" class="tab-btn px-2 pb-3 text-sm font-bold whitespace-nowrap text-slate-400 transition-all border-b-2 border-transparent">অপেক্ষমাণ</button>
            <button onclick="filterStatus('accepted', event)" class="tab-btn px-2 pb-3 text-sm font-bold whitespace-nowrap text-slate-400 transition-all border-b-2 border-transparent">গৃহীত</button>
            <button onclick="filterStatus('payment_pending', event)" class="tab-btn px-2 pb-3 text-sm font-bold whitespace-nowrap text-slate-400 transition-all border-b-2 border-transparent">পেমেন্ট বাকি</button>
            <button onclick="filterStatus('completed', event)" class="tab-btn px-2 pb-3 text-sm font-bold whitespace-nowrap text-slate-400 transition-all border-b-2 border-transparent">সম্পন্ন</button>
        </div>
    </div>

    {{-- কাজের তালিকা --}}
    <div class="px-5 py-6 space-y-4" id="application-list">
        @forelse($applications as $app)
            <div class="app-card bg-white p-5 rounded-3xl border border-slate-100 shadow-sm transition-all" data-status="{{ $app->status }}">
                <div class="space-y-3">
                    <div class="flex justify-between items-start">
                        <h3 class="text-base font-black text-slate-800 leading-tight flex-1 pr-4">{{ $app->job->title }}</h3>
                        <span class="bg-indigo-50 text-indigo-600 font-black text-xs px-3 py-1 rounded-lg shrink-0">৳{{ number_format($app->job->wage) }}</span>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-slate-500">
                            <i class="fa-solid fa-location-dot w-4 text-[10px] text-slate-400"></i>
                            <span class="text-xs font-bold">{{ $app->job->location_name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-money-bill-transfer w-4 text-[10px] text-slate-400"></i>
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-tight">পেমেন্ট পদ্ধতি: 
                                @if($app->job->payment_type == 'in_app')
                                    <span class="text-emerald-600 ml-1 flex-inline items-center gap-1">
                                        <i class="fa-solid fa-mobile-screen text-[10px]"></i> ওয়ালেট
                                    </span>
                                @else
                                    <span class="text-orange-600 ml-1 flex-inline items-center gap-1">
                                        <i class="fa-solid fa-hand-holding-dollar text-[10px]"></i> ক্যাশ
                                    </span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        {{-- স্ট্যাটাস ব্যাজ --}}
                        <div>
                            @if($app->status === 'pending')
                                <div class="flex items-center gap-1.5 bg-slate-100 px-3 py-1.5 rounded-full text-slate-600">
                                    <i class="fa-regular fa-clock text-[10px]"></i>
                                    <span class="text-[10px] font-black uppercase">অপেক্ষমাণ</span>
                                </div>
                            @elseif($app->status === 'accepted')
                                <div class="flex items-center gap-1.5 bg-emerald-50 px-3 py-1.5 rounded-full text-emerald-600">
                                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                                    <span class="text-[10px] font-black uppercase">গৃহীত</span>
                                </div>
                            @elseif($app->status === 'payment_pending')
                                <div class="flex items-center gap-1.5 bg-amber-50 px-3 py-1.5 rounded-full text-amber-600">
                                    <i class="fa-solid fa-hand-holding-dollar text-[10px]"></i>
                                    <span class="text-[10px] font-black uppercase">পেমেন্ট বাকি</span>
                                </div>
                            @elseif($app->status === 'completed')
                                <div class="flex items-center gap-1.5 bg-blue-50 px-3 py-1.5 rounded-full text-blue-600">
                                    <i class="fa-solid fa-check-double text-[10px]"></i>
                                    <span class="text-[10px] font-black uppercase">সম্পন্ন</span>
                                </div>
                            @elseif($app->status === 'rejected')
                                <div class="flex items-center gap-1.5 bg-rose-50 px-3 py-1.5 rounded-full text-rose-600">
                                    <i class="fa-solid fa-circle-xmark text-[10px]"></i>
                                    <span class="text-[10px] font-black uppercase">বাতিল</span>
                                </div>
                            @endif
                        </div>

                        <a href="{{ route('worker.show', $app->job->id) }}" class="text-indigo-600 text-xs font-black flex items-center gap-1 bg-indigo-50/50 px-3 py-1.5 rounded-xl active:scale-95 transition-all">
                            বিস্তারিত <i class="fa-solid fa-chevron-right text-[8px]"></i>
                        </a>
                    </div>
                </div>

                {{-- একশন বাটনস: কর্মস্থলে পৌঁছালে --}}
                @if($app->status === 'accepted' && !$app->arrived_at)
                    <div class="mt-4 pt-4 border-t border-dashed border-slate-100">
                         <form action="{{ route('worker.mark_arrived', $app->id) }}" method="POST">
                            @csrf
                            <button class="w-full bg-slate-900 text-white py-3 rounded-2xl text-xs font-black shadow-lg shadow-slate-200 active:scale-95 transition-transform">
                                <i class="fa-solid fa-person-walking-arrow-right mr-2 text-emerald-400"></i> আমি পৌঁছে গেছি
                            </button>
                         </form>
                    </div>
                @endif

                {{-- পেমেন্ট রিসিভ কনফার্মেশন --}}
                @if($app->status === 'payment_pending')
                    <div class="mt-4 pt-4 border-t border-dashed border-slate-100 bg-amber-50/50 -mx-5 -mb-5 p-5 rounded-b-3xl">
                        <p class="text-[10px] text-amber-700 font-bold mb-3 text-center uppercase tracking-wider">মালিক পেমেন্ট সম্পন্ন করার রিকোয়েস্ট পাঠিয়েছেন</p>
                         <form action="{{ route('worker.payment.confirm', $app->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে আপনি পেমেন্ট বুঝে পেয়েছেন?')">
                            @csrf
                            <button class="w-full bg-amber-500 text-white py-3 rounded-2xl text-xs font-black shadow-lg shadow-amber-200 active:scale-95 transition-transform">
                                <i class="fa-solid fa-circle-check mr-2"></i> পেমেন্ট বুঝে পেয়েছি
                            </button>
                         </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-20">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-briefcase text-slate-300 text-3xl"></i>
                </div>
                <p class="text-slate-400 font-bold">এখনো কোনো আবেদন করেননি</p>
                <a href="{{ route('worker.home') }}" class="text-indigo-600 font-black text-sm mt-2 inline-block">কাজ খুঁজুন</a>
            </div>
        @endforelse
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    .tab-btn.active {
        color: #4f46e5;
        border-bottom-color: #4f46e5;
    }

    @keyframes bounce-short {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-4px); }
    }
    .animate-bounce-short {
        animation: bounce-short 0.5s ease-in-out 1;
    }
</style>

<script>
    function filterStatus(status, event) {
        // ট্যাব বাটনের স্টাইল আপডেট
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active', 'text-indigo-600');
            btn.classList.add('text-slate-400');
            btn.style.borderBottomColor = 'transparent';
        });
        
        const activeBtn = event.currentTarget;
        activeBtn.classList.add('active', 'text-indigo-600');
        activeBtn.classList.remove('text-slate-400');
        activeBtn.style.borderBottomColor = '#4f46e5';

        // কার্ড ফিল্টার করা
        const cards = document.querySelectorAll('.app-card');
        cards.forEach(card => {
            if (status === 'all') {
                card.style.display = 'block';
            } else if (card.getAttribute('data-status') === status) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>
@endsection