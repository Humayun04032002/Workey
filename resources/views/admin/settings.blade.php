@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto pb-20">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-800">সিস্টেম কনফিগারেশন</h1>
        <p class="text-slate-500 font-bold mt-1">Workey প্ল্যাটফর্মের গ্লোবাল সেটিংস এখান থেকে পরিবর্তন করুন।</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-500 text-white p-4 rounded-2xl mb-6 font-bold text-sm shadow-lg shadow-emerald-100 flex items-center gap-3 animate-bounce">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        {{-- পেমেন্ট গ্রহণ নম্বর (নতুন সেকশন) --}}
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <h2 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                <i class="fa-solid fa-mobile-screen-button text-indigo-500"></i> পেমেন্ট মেথড নম্বর (Personal)
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-slate-700 mb-2 ml-1">বিকাশ নম্বর (bKash)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-4 text-slate-400"><i class="fa-solid fa-phone text-xs"></i></span>
                        <input type="text" name="bkash_number" value="{{ $settings['bkash_number'] ?? '' }}" 
                               class="w-full pl-10 pr-5 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 font-black text-slate-700"
                               placeholder="017XXXXXXXX">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2 ml-1 font-bold italic">* এটি ইউজারের ওয়ালেট পেজে দেখাবে।</p>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-2 ml-1">নগদ নম্বর (Nagad)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-4 text-slate-400"><i class="fa-solid fa-phone text-xs"></i></span>
                        <input type="text" name="nagad_number" value="{{ $settings['nagad_number'] ?? '' }}" 
                               class="w-full pl-10 pr-5 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 font-black text-slate-700"
                               placeholder="018XXXXXXXX">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2 ml-1 font-bold italic">* ইউজারের ডিপোজিট করার জন্য ব্যবহৃত হবে।</p>
                </div>
            </div>
        </div>

        {{-- পেমেন্ট ও ফি সেটিংস --}}
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <h2 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                <i class="fa-solid fa-coins text-amber-500"></i> পেমেন্ট ও ফি সেটিংস
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-slate-700 mb-2 ml-1">জব এপ্লাই ফি (Apply Fee)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-4 font-black text-slate-400">৳</span>
                        <input type="number" name="apply_fee" value="{{ $settings['apply_fee'] ?? 20 }}" 
                               class="w-full pl-10 pr-5 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 font-black text-slate-700">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2 ml-1 font-bold italic">* এটি প্রতি আবেদনের জন্য কর্মী থেকে কাটা হবে।</p>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-2 ml-1">সর্বনিম্ন ওয়ালেট ব্যালেন্স</label>
                    <div class="relative">
                        <span class="absolute left-4 top-4 font-black text-slate-400">৳</span>
                        <input type="number" name="min_wallet_balance" value="{{ $settings['min_wallet_balance'] ?? 50 }}" 
                               class="w-full pl-10 pr-5 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 font-black text-slate-700">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2 ml-1 font-bold italic">* কাজে এপ্লাই করতে এই ব্যালেন্স থাকা বাধ্যতামূলক।</p>
                </div>
            </div>
        </div>

        {{-- সিস্টেম কন্ট্রোল --}}
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <h2 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                <i class="fa-solid fa-gears text-indigo-500"></i> সিস্টেম কন্ট্রোল
            </h2>
            
            <div class="space-y-6">
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div>
                        <p class="text-sm font-black text-slate-800">মেইনটেন্যান্স মোড (Maintenance Mode)</p>
                        <p class="text-[11px] font-bold text-slate-400">চালু করলে সাধারণ ইউজাররা অ্যাপ ব্যবহার করতে পারবে না।</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="maintenance_mode" value="1" {{ ($settings['maintenance_mode'] ?? 0) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div>
                        <p class="text-sm font-black text-slate-800">রেজিস্ট্রেশন চালু/বন্ধ</p>
                        <p class="text-[11px] font-bold text-slate-400">নতুন অ্যাকাউন্ট খোলার সুবিধা নিয়ন্ত্রণ করুন।</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="registration_enabled" value="1" {{ ($settings['registration_enabled'] ?? 1) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                    </label>
                </div>
            </div>
        </div>

        {{-- সাপোর্ট সেটিংস --}}
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <h2 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                <i class="fa-solid fa-headset text-rose-500"></i> হেল্প ও সাপোর্ট
            </h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-black text-slate-700 mb-2 ml-1">সাপোর্ট হোয়াটসঅ্যাপ নম্বর</label>
                    <input type="text" name="support_phone" value="{{ $settings['support_phone'] ?? '01700000000' }}" 
                           class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-rose-500 font-bold text-slate-700">
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-[2rem] font-black shadow-xl hover:bg-black transition-all transform hover:-translate-y-1">
            সিস্টেম সেটিংস আপডেট করুন
        </button>
    </form>
</div>
@endsection