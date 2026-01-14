<div class="px-6 pt-8 max-w-lg mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('worker.wallet') }}" class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">←</a>
        <h2 class="text-xl font-bold">পেমেন্ট মেথড</h2>
    </div>

    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-50">
        <p class="text-center text-slate-400 text-xs font-bold uppercase tracking-widest mb-2">পরিশোধ করতে হবে</p>
        <h1 class="text-center text-4xl font-black text-slate-800 mb-8">৳{{ $amount }}</h1>

        <div class="space-y-4">
            <button class="w-full p-4 rounded-2xl border-2 border-slate-50 flex items-center justify-between hover:border-pink-500 transition-all group">
                <div class="flex items-center gap-4">
                    <img src="https://path-to-your-assets/bkash-logo.png" class="w-12 h-12 object-contain" alt="bKash">
                    <span class="font-bold text-slate-700">বিকাশ (bKash)</span>
                </div>
                <i class="fa-solid fa-chevron-right text-slate-300 group-hover:text-pink-500"></i>
            </button>

            <button class="w-full p-4 rounded-2xl border-2 border-slate-50 flex items-center justify-between hover:border-orange-500 transition-all group">
                <div class="flex items-center gap-4">
                    <img src="https://path-to-your-assets/nagad-logo.png" class="w-12 h-12 object-contain" alt="Nagad">
                    <span class="font-bold text-slate-700">নগদ (Nagad)</span>
                </div>
                <i class="fa-solid fa-chevron-right text-slate-300 group-hover:text-orange-500"></i>
            </button>
        </div>

        <p class="mt-8 text-center text-[10px] text-slate-400 font-medium leading-relaxed">
            পেমেন্ট সম্পন্ন করার সাথে সাথে আপনার ওয়ালেটে ব্যালেন্স যোগ হয়ে যাবে। কোনো সমস্যা হলে আমাদের সাপোর্ট সেন্টারে যোগাযোগ করুন।
        </p>
    </div>
</div>