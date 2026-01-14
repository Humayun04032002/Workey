@extends('layouts.employer')

@section('content')
<div class="bg-[#F8FAFC] min-h-screen pb-32" style="font-family: 'Hind Siliguri', sans-serif;">
    
    {{-- Header --}}
    <div class="bg-white px-6 pt-12 pb-6 shadow-sm border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('employer.home') }}" class="w-10 h-10 flex items-center justify-center bg-slate-50 rounded-full text-slate-600 active:scale-90 transition-all">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-xl font-bold text-slate-800">আমার ওয়ালেট</h1>
        </div>
        <div class="text-right">
            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest leading-none">ব্যালেন্স</p>
            <p class="text-lg font-black text-emerald-600 leading-none mt-1">৳{{ number_format(auth()->user()->balance ?? 0) }}</p>
        </div>
    </div>

    <div class="p-4 space-y-6">
        {{-- Wallet Card --}}
        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-[2.5rem] p-8 text-white shadow-xl shadow-emerald-100 relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full"></div>
            <div class="relative z-10">
                <p class="text-emerald-100 text-xs font-bold uppercase tracking-widest">টোটাল ফান্ড</p>
                <h2 class="text-4xl font-black mt-2 italic">৳{{ number_format(auth()->user()->balance ?? 0) }}</h2>
                
                <button onclick="toggleDepositForm()" class="mt-8 bg-white text-emerald-700 px-6 py-3 rounded-2xl font-black text-sm shadow-lg active:scale-95 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-circle-plus"></i> টাকা রিচার্জ করুন
                </button>
            </div>
        </div>

        {{-- Deposit Form (Hidden by Default) --}}
        <div id="deposit_section" class="hidden animate__animated animate__fadeInUp">
            <div class="bg-white rounded-[2rem] p-6 shadow-xl border border-emerald-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-slate-800 font-black">পেমেন্ট মেথড সিলেক্ট করুন</h3>
                    <button onclick="toggleDepositForm()" class="text-slate-300 hover:text-rose-500 transition-colors">
                        <i class="fa-solid fa-circle-xmark text-xl"></i>
                    </button>
                </div>

                {{-- Admin Setting Numbers --}}
                <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl mb-6 flex justify-around text-center">
                    <div>
                        <p class="text-[10px] font-bold text-emerald-700 uppercase">bKash (Personal)</p>
                        <p class="text-sm font-black text-slate-800 tracking-wider">
                            {{ \App\Models\Setting::where('key', 'bkash_number')->value('value') ?? 'Not Set' }}
                        </p>
                    </div>
                    <div class="w-[1px] bg-emerald-200"></div>
                    <div>
                        <p class="text-[10px] font-bold text-orange-700 uppercase">Nagad (Personal)</p>
                        <p class="text-sm font-black text-slate-800 tracking-wider">
                            {{ \App\Models\Setting::where('key', 'nagad_number')->value('value') ?? 'Not Set' }}
                        </p>
                    </div>
                </div>

                <form action="{{ route('employer.deposit') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="method" value="bkash" class="peer hidden" required checked>
                            <div class="peer-checked:border-emerald-600 peer-checked:bg-emerald-50 border-2 border-slate-50 p-3 rounded-2xl text-center font-bold text-sm text-slate-600">bKash</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="method" value="nagad" class="peer hidden">
                            <div class="peer-checked:border-orange-500 peer-checked:bg-orange-50 border-2 border-slate-50 p-3 rounded-2xl text-center font-bold text-sm text-slate-600">Nagad</div>
                        </label>
                    </div>

                    <input type="number" name="amount" id="recharge_amount" required min="10" placeholder="টাকার পরিমাণ (৳)" 
                           class="w-full px-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-emerald-500 font-bold text-sm">

                    <input type="text" name="sender_number" required placeholder="যে নম্বর থেকে টাকা পাঠিয়েছেন" 
                           class="w-full px-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-emerald-500 font-bold text-sm">

                    <input type="text" name="transaction_id" required placeholder="Transaction ID (TrxID)" 
                           class="w-full px-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-emerald-500 font-bold text-sm uppercase">

                    <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black shadow-lg active:scale-95 transition-all">
                        রিচার্জ রিকোয়েস্ট পাঠান
                    </button>
                </form>
            </div>
        </div>

        {{-- History Section --}}
        <div>
            <h3 class="font-black text-slate-400 text-[11px] uppercase tracking-widest mb-4 ml-2">সাম্প্রতিক লেনদেন</h3>
            <div class="space-y-3">
                @forelse($history as $h)
                <div class="bg-white p-4 rounded-2xl border border-slate-50 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg {{ $h->status == 'approved' ? 'bg-emerald-50 text-emerald-500' : ($h->status == 'rejected' ? 'bg-rose-50 text-rose-500' : 'bg-amber-50 text-amber-500') }}">
                            <i class="fa-solid {{ $h->status == 'approved' ? 'fa-check-double' : ($h->status == 'rejected' ? 'fa-xmark' : 'fa-clock') }}"></i>
                        </div>
                        <div>
                            <p class="text-[13px] font-black text-slate-800">{{ ucfirst($h->method) }} রিচার্জ</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">{{ $h->created_at->format('d M, Y • h:i A') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-black text-slate-800">৳{{ number_format($h->amount) }}</p>
                        <p class="text-[9px] font-bold uppercase {{ $h->status == 'approved' ? 'text-emerald-500' : ($h->status == 'rejected' ? 'text-rose-500' : 'text-amber-500') }}">
                            {{ $h->status == 'approved' ? 'সফল' : ($h->status == 'rejected' ? 'বাতিল' : 'পেন্ডিং') }}
                        </p>
                    </div>
                </div>
                @empty
                <div class="py-12 text-center bg-white rounded-3xl border border-dashed border-slate-200">
                    <p class="text-slate-400 text-sm font-bold">কোনো হিস্টোরি পাওয়া যায়নি</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    function toggleDepositForm() {
        const section = document.getElementById('deposit_section');
        section.classList.toggle('hidden');
        if(!section.classList.contains('hidden')) {
            section.scrollIntoView({ behavior: 'smooth' });
        }
    }
</script>
@endsection