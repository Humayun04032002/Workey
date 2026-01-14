@extends('layouts.worker')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<div class="bg-slate-50 min-h-screen pb-32 no-print">
    {{-- হেডার --}}
    <div class="bg-white px-6 pt-12 pb-6 rounded-b-[3rem] shadow-sm mb-8">
        <div class="flex items-center justify-between">
            <a href="{{ route('worker.income.history') }}" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-lg font-black text-slate-800">পেমেন্ট রিসিপ্ট</h2>
            <div class="w-10"></div>
        </div>
    </div>

    <div class="px-6">
        {{-- রিসিপ্ট কার্ড (এই অংশটুকুই ছবি হবে) --}}
        <div id="receipt-capture" class="bg-white rounded-[3rem] shadow-xl border border-slate-100 overflow-hidden relative p-8">
            {{-- ডেকোরেশন সার্কেল --}}
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-50 rounded-full opacity-50"></div>
            
            <div class="relative">
                {{-- স্ট্যাটাস আইকন --}}
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-emerald-500 rounded-3xl flex items-center justify-center text-white mx-auto mb-4 shadow-lg rotate-3">
                        <i class="fa-solid fa-check-double text-3xl -rotate-3"></i>
                    </div>
                    <h3 class="text-3xl font-black text-slate-800">৳{{ number_format($income->job->wage) }}</h3>
                    <p class="text-[10px] text-emerald-500 font-black uppercase tracking-[0.2em] mt-1">সফল পেমেন্ট</p>
                </div>

                <div class="space-y-6 pt-6 border-t border-dashed border-slate-200">
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">কাজের বিবরণ</p>
                        <div class="flex justify-between items-start">
                            <span class="text-xs font-black text-slate-700 leading-tight">{{ $income->job->title }}</span>
                            <span class="text-[10px] font-mono font-bold text-slate-400">#WRK-{{ 1000 + $income->id }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 py-4 bg-slate-50 rounded-2xl px-4 border border-slate-100">
                        <div>
                            <p class="text-[8px] font-bold text-slate-400 uppercase mb-1">নিয়োগকারী</p>
                            <p class="text-[11px] font-black text-slate-700">{{ $income->job->employer->name }}</p>
                            <p class="text-[10px] font-bold text-indigo-600">{{ $income->job->employer->phone }}</p>
                        </div>
                        <div class="text-right border-l border-slate-200 pl-4">
                            <p class="text-[8px] font-bold text-slate-400 uppercase mb-1">কর্মী</p>
                            <p class="text-[11px] font-black text-slate-700">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] font-bold text-indigo-600">{{ auth()->user()->phone }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">পেমেন্ট পদ্ধতি</span>
                            <span class="text-[9px] font-black text-slate-700">
                                {{ ($income->job->payment_method == 'wallet') ? 'ইন-অ্যাপ ওয়ালেট' : 'নগদ পেমেন্ট' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">তারিখ ও সময়</span>
                            <span class="text-[10px] font-bold text-slate-700">{{ $income->updated_at->format('d M, Y | h:i A') }}</span>
                        </div>
                    </div>
                </div>

                {{-- কিউআর কোড --}}
                <div class="mt-10 flex flex-col items-center justify-center">
                    <div class="p-3 bg-white rounded-2xl border border-slate-100 shadow-sm text-slate-800">
                        <i class="fa-solid fa-qrcode text-4xl"></i>
                    </div>
                    <p class="text-[8px] text-slate-400 font-bold uppercase mt-3 tracking-[0.3em] text-center">Workey Digital Receipt</p>
                </div>
            </div>
        </div>

        {{-- অ্যাকশন বাটন --}}
        <div class="grid grid-cols-2 gap-4 mt-8">
            <button onclick="downloadAsImage()" class="bg-slate-900 text-white py-4 rounded-2xl font-black text-xs flex items-center justify-center gap-2 active:scale-95 transition-all">
                <i class="fa-solid fa-download"></i> ইমেজ ডাউনলোড
            </button>
            <button onclick="shareAsImage()" class="bg-indigo-600 text-white py-4 rounded-2xl font-black text-xs flex items-center justify-center gap-2 active:scale-95 transition-all">
                <i class="fa-solid fa-share-nodes"></i> বিকাশ স্টাইলে শেয়ার
            </button>
        </div>
    </div>
</div>

<script>
    // কার্ডটিকে ছবিতে রূপান্তর করার ফাংশন
    async function generateImage() {
        const element = document.getElementById('receipt-capture');
        const canvas = await html2canvas(element, {
            scale: 3, // হাই কোয়ালিটি ইমেজ
            backgroundColor: "#f8fafc", // স্লেট ব্যাকগ্রাউন্ড
            borderRadius: 40
        });
        return canvas;
    }

    // ডাউনলোড ফাংশন
    async function downloadAsImage() {
        const canvas = await generateImage();
        const link = document.createElement('a');
        link.download = 'Workey-Receipt-{{ $income->id }}.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    }

    // শেয়ার ফাংশন (বিকাশের মতো ছবি শেয়ার)
    async function shareAsImage() {
        const canvas = await generateImage();
        canvas.toBlob(async (blob) => {
            const file = new File([blob], 'Receipt.png', { type: 'image/png' });
            
            if (navigator.share && navigator.canShare({ files: [file] })) {
                try {
                    await navigator.share({
                        files: [file],
                        title: 'পেমেন্ট রিসিপ্ট - Workey',
                        text: 'আমার কাজের পেমেন্ট রিসিপ্ট কার্ড।'
                    });
                } catch (err) {
                    console.error('শেয়ার করতে সমস্যা হয়েছে:', err);
                }
            } else {
                alert('আপনার ব্রাউজার ছবি শেয়ার সাপোর্ট করে না। ডাউনলোড করে শেয়ার করুন।');
            }
        }, 'image/png');
    }
</script>

<style>
    /* প্রিন্ট এবং মোবাইল ভিউ ঠিক করার জন্য */
    #receipt-capture {
        max-width: 500px;
        margin: 0 auto;
    }
</style>
@endsection