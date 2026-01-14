@extends('layouts.worker')

@section('content')
<div class="max-w-md mx-auto pb-20 px-4">
    {{-- হেডার অংশ --}}
    <div class="text-center my-8">
        <div class="w-20 h-20 bg-indigo-600 text-white rounded-[2rem] flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-200">
            <i class="fa-solid fa-user-shield text-3xl"></i>
        </div>
        <h2 class="text-2xl font-black text-slate-800">পরিচয় যাচাইকরণ</h2>
        <p class="text-slate-500 font-bold text-sm mt-1">নিরাপদ কাজের পরিবেশ নিশ্চিত করতে আপনার এনআইডি ও লাইভ ফটো জমা দিন</p>
    </div>

    {{-- স্ট্যাটাস মেসেজ --}}
    @if(auth()->user()->verification_status == 'pending')
        <div class="bg-amber-50 border border-amber-100 p-6 rounded-[2rem] text-center mb-6">
            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fa-solid fa-hourglass-half animate-spin"></i>
            </div>
            <h3 class="font-black text-amber-800">আবেদন প্রক্রিয়াধীন</h3>
            <p class="text-xs text-amber-600 font-bold mt-1">আপনার তথ্যগুলো অ্যাডমিন টিম রিভিউ করছে। ২৪-৪৮ ঘণ্টার মধ্যে ফলাফল জানানো হবে।</p>
        </div>
    @elseif(auth()->user()->verification_status == 'verified')
        <div class="bg-emerald-50 border border-emerald-100 p-6 rounded-[2rem] text-center mb-6">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3 text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h3 class="font-black text-emerald-800">আপনি ভেরিফাইড!</h3>
            <p class="text-xs text-emerald-600 font-bold mt-1">আপনার অ্যাকাউন্টটি এখন সম্পূর্ণ ভেরিফাইড। আপনি এখন যেকোনো কাজে আবেদন করতে পারবেন।</p>
        </div>
    @else
        {{-- রিজেক্টেড হলে কারণ দেখানো --}}
        @if(auth()->user()->rejection_reason)
            <div class="bg-rose-50 border border-rose-100 p-4 rounded-2xl mb-6 text-rose-600 text-xs font-bold">
                <i class="fa-solid fa-circle-exclamation mr-1"></i> আপনার আগের আবেদনটি বাতিল হয়েছে। কারণ: {{ auth()->user()->rejection_reason }}
            </div>
        @endif

        <form action="{{ route('worker.verify.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            {{-- NID নম্বর --}}
            <div class="bg-white p-2 rounded-3xl shadow-sm">
                <label class="block text-[10px] font-black text-slate-400 uppercase ml-4 mt-2">NID কার্ড নম্বর</label>
                <input type="text" name="nid_number" placeholder="Enter NID Number" 
                       class="w-full px-4 py-3 rounded-2xl border-none focus:ring-0 font-black text-slate-700" required>
            </div>

            {{-- Live Photo/Selfie Section --}}
            <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-50 text-center">
                <p class="text-[10px] font-black text-slate-400 uppercase mb-4 tracking-widest text-left">আপনার লাইভ ছবি (Selfie)</p>
                <label class="relative cursor-pointer block">
                    <div id="selfie-preview" class="w-32 h-32 bg-slate-50 rounded-full mx-auto border-4 border-dashed border-slate-200 flex items-center justify-center overflow-hidden">
                        <i class="fa-solid fa-camera text-slate-300 text-3xl"></i>
                    </div>
                    <div class="absolute bottom-0 right-1/3 bg-indigo-600 text-white w-8 h-8 rounded-full flex items-center justify-center border-4 border-white">
                        <i class="fa-solid fa-plus text-xs"></i>
                    </div>
                    <input type="file" name="live_photo" id="live_photo" class="hidden" accept="image/*" required onchange="previewImage(this, 'selfie-preview')">
                </label>
                <p class="text-[9px] text-slate-400 mt-3 font-bold italic">চেহারার স্পষ্ট ছবি তুলুন</p>
            </div>

            {{-- NID Sides --}}
            <div class="grid grid-cols-2 gap-4">
                {{-- Front Side --}}
                <div class="bg-white p-4 rounded-[2rem] shadow-sm text-center border border-slate-50">
                    <p class="text-[9px] font-black text-slate-400 uppercase mb-3">NID সামনের দিক</p>
                    <label class="cursor-pointer block">
                        <div id="front-preview" class="h-24 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden">
                            <i class="fa-solid fa-id-card text-slate-300 text-2xl"></i>
                        </div>
                        <input type="file" name="nid_front" class="hidden" accept="image/*" required onchange="previewImage(this, 'front-preview')">
                    </label>
                </div>

                {{-- Back Side --}}
                <div class="bg-white p-4 rounded-[2rem] shadow-sm text-center border border-slate-50">
                    <p class="text-[9px] font-black text-slate-400 uppercase mb-3">NID পিছনের দিক</p>
                    <label class="cursor-pointer block">
                        <div id="back-preview" class="h-24 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden">
                            <i class="fa-solid fa-id-card text-slate-300 text-2xl"></i>
                        </div>
                        <input type="file" name="nid_back" class="hidden" accept="image/*" required onchange="previewImage(this, 'back-preview')">
                    </label>
                </div>
            </div>

            <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-[2rem] font-black shadow-xl hover:bg-black transition-all transform active:scale-95 flex items-center justify-center gap-3">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                তথ্য সাবমিট করুন
            </button>
        </form>
    @endif
</div>

{{-- Image Preview Script --}}
<script>
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                preview.classList.remove('border-dashed');
                preview.classList.add('border-solid', 'border-indigo-100');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection