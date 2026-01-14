@extends('layouts.worker')

@section('content')
<div class="bg-slate-50 min-h-screen pb-32">
    {{-- টপ ব্যালেন্স কার্ড --}}
    <div class="bg-indigo-600 px-6 pt-12 pb-20 rounded-b-[3rem] shadow-lg text-white">
        <div class="flex flex-col items-center text-center">
            <p class="text-indigo-100 text-sm font-bold uppercase tracking-widest">বর্তমান ব্যালেন্স</p>
            <h1 class="text-5xl font-black mt-2">৳{{ number_format(auth()->user()->balance ?? 0) }}</h1>
            
            {{-- মেইন ডিপোজিট বাটন --}}
            <button onclick="toggleDepositForm()" class="mt-8 bg-white text-indigo-600 px-8 py-4 rounded-2xl font-black shadow-xl flex items-center gap-3 active:scale-95 transition-all">
                <i class="fa-solid fa-circle-plus text-xl"></i>
                টাকা জমা দিন
            </button>
        </div>
    </div>

    {{-- ডিপোজিট ফর্ম সেকশন (শুরুতে হিডেন থাকবে) --}}
    <div id="deposit_section" class="px-6 -mt-8 hidden animate__animated animate__fadeInUp">
        <div class="bg-white rounded-[2.5rem] p-8 shadow-2xl border border-slate-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-slate-800 font-black text-lg">রিচার্জ কনফিগারেশন</h3>
                <button onclick="toggleDepositForm()" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <i class="fa-solid fa-circle-xmark text-2xl"></i>
                </button>
            </div>
            
            {{-- ডাইনামিক পেমেন্ট মেথড নম্বর --}}
            <div class="bg-amber-50 border border-amber-100 p-4 rounded-2xl mb-6">
                <p class="text-[11px] font-black text-amber-700 uppercase mb-2 text-center">পেমেন্ট মেথড (Personal)</p>
                <div class="flex justify-around gap-2 text-center">
                    <div>
                        <p class="text-xs font-bold text-slate-600">bKash</p>
                        <p class="text-sm font-black text-slate-800">
                            {{ \App\Models\Setting::where('key', 'bkash_number')->value('value') ?? 'Not Set' }}
                        </p>
                    </div>
                    <div class="w-[1px] bg-amber-200"></div>
                    <div>
                        <p class="text-xs font-bold text-slate-600">Nagad</p>
                        <p class="text-sm font-black text-slate-800">
                            {{ \App\Models\Setting::where('key', 'nagad_number')->value('value') ?? 'Not Set' }}
                        </p>
                    </div>
                </div>
                <p class="text-[9px] text-amber-600 mt-2 text-center italic">* প্রথমে উপরের নাম্বারে সেন্ড মানি করে নিচে তথ্য দিন</p>
            </div>

            <form action="{{ route('worker.deposit') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <label class="cursor-pointer">
                        <input type="radio" name="method" value="bkash" class="peer hidden" required checked>
                        <div class="peer-checked:border-indigo-600 peer-checked:bg-indigo-50 border-2 border-slate-50 p-3 rounded-2xl text-center transition-all">
                            <p class="font-black text-sm text-slate-700">bKash</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="method" value="nagad" class="peer hidden">
                        <div class="peer-checked:border-orange-500 peer-checked:bg-orange-50 border-2 border-slate-50 p-3 rounded-2xl text-center transition-all">
                            <p class="font-black text-sm text-slate-700">Nagad</p>
                        </div>
                    </label>
                </div>

                <div class="grid grid-cols-3 gap-2 mb-4">
                    <button type="button" onclick="setAmount(50)" class="bg-slate-50 py-2 rounded-xl text-xs font-black text-slate-600 hover:bg-indigo-600 hover:text-white transition-all">৳৫০</button>
                    <button type="button" onclick="setAmount(100)" class="bg-slate-50 py-2 rounded-xl text-xs font-black text-slate-600 hover:bg-indigo-600 hover:text-white transition-all">৳১০০</button>
                    <button type="button" onclick="setAmount(500)" class="bg-slate-50 py-2 rounded-xl text-xs font-black text-slate-600 hover:bg-indigo-600 hover:text-white transition-all">৳৫০০</button>
                </div>

                <div class="space-y-4">
                    <input type="number" name="amount" id="recharge_amount" required min="10" placeholder="টাকার পরিমাণ" 
                           class="w-full px-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-slate-700 text-sm">

                    <input type="text" name="sender_number" required placeholder="আপনার বিকাশ/নগদ নম্বর" 
                           class="w-full px-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-slate-700 text-sm">

                    <input type="text" name="transaction_id" required placeholder="Transaction ID (TrxID)" 
                           class="w-full px-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-slate-700 text-sm uppercase">
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-black shadow-lg shadow-indigo-100 active:scale-95 transition-all mt-6">
                    কনফার্ম রিচার্জ
                </button>
            </form>
        </div>
    </div>

    {{-- লেনদেনের ইতিহাস --}}
    <div class="px-6 mt-10">
        <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-4 ml-2">লেনদেনের ইতিহাস</h3>
        
        <div class="space-y-3">
            @forelse($history as $h)
            <div class="bg-white p-4 rounded-3xl border border-slate-100 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 {{ $h->status == 'approved' ? 'bg-emerald-50 text-emerald-500' : ($h->status == 'rejected' ? 'bg-rose-50 text-rose-500' : 'bg-amber-50 text-amber-500') }} rounded-xl flex items-center justify-center text-lg">
                        <i class="fa-solid {{ $h->status == 'approved' ? 'fa-check' : ($h->status == 'rejected' ? 'fa-xmark' : 'fa-clock') }}"></i>
                    </div>
                    <div>
                        <p class="text-sm font-black text-slate-800">
                            {{ ucfirst($h->method) }} রিচার্জ 
                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-slate-100 ml-1">#{{ $h->transaction_id }}</span>
                        </p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $h->created_at->format('d M, Y - h:i A') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-black text-slate-800">৳{{ number_format($h->amount) }}</p>
                    <p class="text-[9px] font-bold {{ $h->status == 'approved' ? 'text-emerald-500' : ($h->status == 'rejected' ? 'text-rose-500' : 'text-amber-500') }}">
                        {{ $h->status == 'approved' ? 'সফল' : ($h->status == 'rejected' ? 'বাতিল' : 'পেন্ডিং') }}
                    </p>
                </div>
            </div>
            @empty
            <div class="bg-white p-10 rounded-3xl border border-dashed border-slate-200 text-center">
                <p class="text-slate-400 font-bold text-sm">এখনো কোনো লেনদেন হয়নি</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- এনিমেশন লাইব্রেরি --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<script>
    function toggleDepositForm() {
        const section = document.getElementById('deposit_section');
        section.classList.toggle('hidden');
        if(!section.classList.contains('hidden')) {
            section.scrollIntoView({ behavior: 'smooth' });
        }
    }

    function setAmount(val) {
        document.getElementById('recharge_amount').value = val;
    }
</script>
@endsection