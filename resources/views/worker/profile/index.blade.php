@extends('layouts.worker')

@section('content')
<div class="bg-slate-50 min-h-screen pb-32">
    {{-- সাকসেস মেসেজ নোটিফিকেশন --}}
    @if(session('success'))
        <div class="fixed top-5 left-1/2 -translate-x-1/2 z-[100] w-[90%] max-w-md">
            <div class="bg-emerald-500 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 animate-bounce">
                <i class="fa-solid fa-circle-check text-xl"></i>
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- হেডার ও প্রোফাইল কার্ড --}}
    <div class="bg-white px-6 pt-12 pb-8 rounded-b-[3rem] shadow-sm border-b border-slate-100">
        <div class="flex flex-col items-center">
            <div class="relative">
                {{-- ভেরিফাইড হলে এনিমেটেড বর্ডার --}}
                <div class="w-28 h-28 rounded-full border-4 {{ auth()->user()->verification_status == 'verified' ? 'border-indigo-500' : 'border-slate-100' }} overflow-hidden shadow-xl bg-white relative">
                    <img src="{{ auth()->user()->profile_photo ? asset('storage/'.auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=6366f1&color=fff' }}" 
                         class="w-full h-full object-cover">
                </div>
                
                {{-- ভেরিফাইড ব্লু টিক --}}
                @if(auth()->user()->verification_status == 'verified')
                <div class="absolute bottom-1 right-1 bg-blue-500 text-white w-7 h-7 rounded-full flex items-center justify-center border-2 border-white shadow-lg z-10">
                    <i class="fa-solid fa-check text-[10px]"></i>
                </div>
                @endif
            </div>
            
            <div class="mt-4 text-center">
                <h2 class="text-xl font-black text-slate-800">{{ auth()->user()->name }}</h2>
                <p class="text-xs text-slate-400 font-bold mt-1">{{ auth()->user()->category ?? 'পেশা সেট করা নেই' }}</p>
                
                <div class="flex items-center justify-center gap-1 mt-3 text-amber-500 bg-amber-50 px-3 py-1 rounded-full w-fit mx-auto">
                    <i class="fa-solid fa-star text-xs"></i>
                    <span class="text-xs font-black">{{ number_format(auth()->user()->averageRating(), 1) }} ({{ auth()->user()->reviews()->count() }} রিভিউ)</span>
                </div>
            </div>
        </div>

        {{-- ব্যালেন্স ও ইনকাম স্ট্যাটাস --}}
        <div class="grid grid-cols-2 gap-4 mt-8">
            <a href="{{ route('worker.wallet') }}" class="bg-indigo-600 p-4 rounded-3xl text-white shadow-lg shadow-indigo-100 active:scale-95 transition-all">
                <p class="text-[10px] opacity-80 uppercase font-bold tracking-wider">ব্যালেন্স</p>
                <div class="flex items-center justify-between mt-1">
                    <h4 class="text-lg font-black">৳{{ number_format(auth()->user()->balance) }}</h4>
                    <i class="fa-solid fa-chevron-right text-[10px] opacity-50"></i>
                </div>
            </a>

            {{-- মোট আয় সেকশন - এখানে ক্লিক করলে হিস্ট্রি দেখাবে --}}
            <a href="{{ route('worker.income.history') }}" class="bg-slate-900 p-4 rounded-3xl text-white border border-slate-800 active:scale-95 transition-all">
                <p class="text-[10px] opacity-80 uppercase font-bold tracking-wider">মোট আয়</p>
                <div class="flex items-center justify-between mt-1">
                    <h4 class="text-lg font-black text-emerald-400">৳{{ number_format($totalEarnings ?? 0) }}</h4>
                    <i class="fa-solid fa-chevron-right text-[10px] opacity-50"></i>
                </div>
            </a>
        </div>
    </div>

    <div class="px-6 mt-8 space-y-6">
        {{-- অ্যাকাউন্ট ভেরিফিকেশন স্ট্যাটাস সেকশন --}}
        <div>
            <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-4">অ্যাকাউন্ট স্ট্যাটাস</h3>
            
            @if(auth()->user()->verification_status == 'verified')
                <div class="bg-emerald-50 border border-emerald-100 rounded-[2rem] p-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                        <div>
                            <p class="text-sm font-black text-slate-800">অ্যাকাউন্ট ভেরিফাইড</p>
                            <p class="text-[10px] text-emerald-600 font-bold uppercase">আপনি এখন কাজ করতে পারবেন</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-circle-check text-emerald-500 text-xl"></i>
                </div>
            @elseif(auth()->user()->verification_status == 'pending')
                <div class="bg-amber-50 border border-amber-100 rounded-[2rem] p-5 flex items-center justify-between">
                    <div class="flex items-center gap-3 text-amber-700">
                        <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                        <div>
                            <p class="text-sm font-black">ভেরিফিকেশন পেন্ডিং</p>
                            <p class="text-[10px] font-bold uppercase tracking-tight">অ্যাডমিন রিভিউ করছে...</p>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('worker.verify') }}" class="bg-gradient-to-r from-rose-500 to-orange-500 rounded-[2rem] p-5 flex items-center justify-between shadow-lg shadow-rose-100 active:scale-95 transition-all group">
                    <div class="flex items-center gap-3 text-white">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-md">
                            <i class="fa-solid fa-id-card text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm font-black">ভেরিফাই করুন</p>
                            <p class="text-[10px] opacity-80 uppercase font-bold">আইডি কার্ড সাবমিট করুন</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-white/50 group-hover:translate-x-1 transition-transform"></i>
                </a>
            @endif
        </div>

        {{-- প্রোফাইল ডিটেইলস --}}
        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 space-y-5">
            <div class="flex justify-between items-center">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">ব্যক্তিগত তথ্য</h3>
                <a href="{{ route('worker.profile.edit') }}" class="text-xs font-bold text-indigo-600 bg-indigo-50 px-4 py-2 rounded-full active:scale-90 transition-all">এডিট করুন</a>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                        <i class="fa-solid fa-envelope text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] text-slate-400 font-bold uppercase">ইমেইল</p>
                        <p class="text-sm font-bold text-slate-700 truncate">{{ auth()->user()->email ?? 'যোগ করা হয়নি' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                        <i class="fa-solid fa-phone text-xs"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">ফোন</p>
                        <p class="text-sm font-bold text-slate-700">{{ auth()->user()->phone }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                        <i class="fa-solid fa-location-dot text-xs"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">ঠিকানা</p>
                        <p class="text-sm font-bold text-slate-700">{{ auth()->user()->address ?? 'ঠিকানা দেওয়া নেই' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- লগ আউট বাটন --}}
        <button type="button" onclick="confirmLogout()" 
                class="w-full bg-rose-50 text-rose-500 rounded-[2rem] p-5 flex items-center justify-center gap-3 font-black active:scale-95 transition-all">
            <i class="fa-solid fa-power-off"></i>
            <span class="text-sm uppercase tracking-widest">লগ আউট</span>
        </button>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

<script>
    function confirmLogout() {
        if(confirm('আপনি কি নিশ্চিত যে লগ আউট করতে চান?')) {
            document.getElementById('logout-form').submit();
        }
    }
</script>
@endsection