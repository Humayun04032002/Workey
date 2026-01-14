<div class="mt-10">
    <h3 class="text-lg font-black text-slate-800 mb-5 flex items-center gap-2">
        <span class="w-1.5 h-6 bg-indigo-600 rounded-full"></span> লেনদেনের ইতিহাস
    </h3>

    <div class="space-y-4">
        {{-- আমরা এখানে ট্রানজ্যাকশনগুলো ফিল্টার করছি যেন শুধু ওয়ালেট বা ইন-অ্যাপ পেমেন্টগুলো দেখায় --}}
        @php
            $inAppTransactions = auth()->user()->transactions()
                ->where('status', 'success') // শুধুমাত্র সফল লেনদেন
                ->latest()
                ->get();
        @endphp

        @forelse($inAppTransactions as $trx)
            <div class="bg-white p-5 rounded-3xl border border-slate-50 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-4">
                    {{-- লেনদেনের ধরণ অনুযায়ী আইকন --}}
                    <div class="w-12 h-12 {{ $trx->type == 'deposit' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} rounded-2xl flex items-center justify-center text-lg shadow-sm border border-white">
                        <i class="fa-solid {{ $trx->type == 'deposit' ? 'fa-arrow-down-left' : 'fa-arrow-up-right' }}"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-slate-800 leading-tight">{{ $trx->purpose }}</h4>
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="text-[9px] font-black text-indigo-500 bg-indigo-50 px-1.5 py-0.5 rounded uppercase tracking-tighter">In-App</span>
                            <p class="text-[10px] font-bold text-slate-400 border-l border-slate-200 pl-1.5">
                                {{ $trx->created_at->format('d M, Y | h:i A') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-black {{ $trx->type == 'deposit' ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $trx->type == 'deposit' ? '+' : '-' }} ৳{{ number_format($trx->amount, 0) }}
                    </p>
                    <p class="text-[8px] font-black text-slate-300 uppercase tracking-[0.1em] mt-1 italic">Verified</p>
                </div>
            </div>
        @empty
            <div class="text-center py-10 bg-white rounded-[2.5rem] border border-dashed border-slate-200">
                <div class="text-slate-200 text-5xl mb-4">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">এখনো কোনো ইন-অ্যাপ লেনদেন হয়নি</p>
            </div>
        @endforelse
    </div>
</div>