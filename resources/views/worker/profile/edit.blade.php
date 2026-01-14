@extends('layouts.worker')

@section('content')
<div class="bg-slate-50 min-h-screen pb-32">
    <div class="bg-white px-6 pt-12 pb-8 rounded-b-[3rem] shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('worker.profile.index') }}" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-lg font-black text-slate-800">প্রোফাইল এডিট</h2>
            <div class="w-10"></div>
        </div>

        <form action="{{ route('worker.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="flex flex-col items-center mb-10">
                <div class="relative">
                    <div class="w-32 h-32 rounded-full border-4 border-indigo-600 p-1 overflow-hidden shadow-2xl">
                        <img src="{{ auth()->user()->profile_photo ? asset('storage/'.auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}" 
                             id="preview-img" class="w-full h-full object-cover rounded-full">
                    </div>
                    <label for="profile_photo" class="absolute bottom-0 right-0 bg-indigo-600 text-white w-10 h-10 rounded-full flex items-center justify-center border-4 border-white cursor-pointer">
                        <i class="fa-solid fa-camera"></i>
                        <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*" onchange="previewFile()">
                    </label>
                </div>
                <p class="text-[10px] text-slate-400 font-bold mt-4 uppercase">ছবি পরিবর্তন করতে ক্যামেরায় ট্যাপ করুন</p>
            </div>

            <div class="space-y-5">
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase ml-2">আপনার নাম</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                           class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase ml-2">ইমেইল এড্রেস</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                           class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase ml-2">কাজের ধরন</label>
                    <select name="category" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500 appearance-none">
                        @foreach(['রাজমিস্ত্রি', 'ইলেকট্রিশিয়ান', 'প্লাম্বার', 'ক্লিনার', 'ড্রাইভার', 'নির্মাণ শ্রমিক'] as $cat)
                            <option value="{{ $cat }}" {{ (old('category', auth()->user()->category) == $cat) ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase ml-2">বর্তমান ঠিকানা</label>
                    <textarea name="address" rows="3" 
                              class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500 transition-all">{{ old('address', auth()->user()->address) }}</textarea>
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white rounded-2xl py-5 mt-10 font-black shadow-xl shadow-indigo-100 active:scale-95 transition-all">
                সেভ করুন
            </button>
        </form>
    </div>
</div>

<script>
    function previewFile() {
        const preview = document.querySelector('#preview-img');
        const file = document.querySelector('#profile_photo').files[0];
        const reader = new FileReader();
        reader.addEventListener("load", function () { preview.src = reader.result; }, false);
        if (file) { reader.readAsDataURL(file); }
    }
</script> 
@endsection