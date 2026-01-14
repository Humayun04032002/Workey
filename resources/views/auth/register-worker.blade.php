@extends('layouts.auth')

@section('content')
<div class="relative bg-white min-h-[600px] p-6 sm:p-10 rounded-[2.5rem] shadow-2xl shadow-blue-100/50 border border-white/50 overflow-hidden">
    
    <div class="absolute -top-24 -right-24 w-64 h-64 bg-gradient-to-br from-blue-100 to-transparent rounded-full blur-3xl opacity-50"></div>

    <div class="relative z-10">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-50 rounded-2xl mb-3">
                <span class="text-3xl animate-bounce-slow">🛠️</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-800">কাজের তথ্য</h1>
            <p class="text-slate-400 font-medium text-sm">আপনার দক্ষতা ও মজুরি সেট করুন</p>
        </div>

        <form action="{{ route('register.complete') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <input type="hidden" name="role" value="worker">

            <div class="flex items-center justify-center gap-2 mb-8">
                <div class="h-1.5 w-4 rounded-full bg-blue-200"></div>
                <div class="h-1.5 w-8 rounded-full bg-blue-600"></div>
                <div class="h-1.5 w-4 rounded-full bg-slate-100"></div>
            </div>

            <div>
                <label class="block text-slate-700 text-xs font-bold mb-4 ml-1 uppercase tracking-wide">পেশা নির্বাচন করুন</label>
                <div class="grid grid-cols-2 gap-3">
                    @php
                        $categories = [
                            ['id' => 'Electrician', 'name' => 'ইলেকট্রিশিয়ান', 'icon' => '⚡'],
                            ['id' => 'Plumber', 'name' => 'প্লাম্বার', 'icon' => '🚰'],
                            ['id' => 'Mason', 'name' => 'মিস্ত্রি', 'icon' => '🧱'],
                            ['id' => 'Labor', 'name' => 'লেবার', 'icon' => '💪'],
                        ];
                    @endphp

                    @foreach($categories as $cat)
                    <label class="cursor-pointer group">
                        <input type="radio" name="category" value="{{ $cat['id'] }}" class="hidden peer" required>
                        <div class="p-4 border-2 border-slate-50 rounded-2xl bg-slate-50/50 text-center transition-all duration-300 peer-checked:border-blue-600 peer-checked:bg-white peer-checked:shadow-lg peer-checked:shadow-blue-100 group-hover:bg-slate-100">
                            <div class="text-2xl mb-1">{{ $cat['icon'] }}</div>
                            <div class="text-xs font-bold text-slate-600 peer-checked:text-blue-700">{{ $cat['name'] }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="group">
                <label class="block text-slate-700 text-xs font-bold mb-2 ml-1 uppercase tracking-wide">দৈনিক মজুরি (টাকা)</label>
                <div class="relative transition-all duration-300">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <span class="text-slate-400 font-bold">৳</span>
                    </div>
                    <input type="number" name="expected_wage" placeholder="৫০০" 
                        class="w-full pl-10 pr-20 py-4 bg-slate-50 border-2 border-transparent rounded-2xl outline-none focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all font-bold text-slate-700 text-lg" required>
                    <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none">
                        <span class="text-slate-400 text-xs font-bold">প্রতি দিন</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full mt-6 bg-gradient-to-r from-blue-700 to-indigo-800 text-white py-4 rounded-2xl font-bold text-lg shadow-xl shadow-blue-200 hover:shadow-blue-300 hover:-translate-y-1 transition-all duration-300 active:scale-95 flex items-center justify-center gap-2 group">
                রেজিস্ট্রেশন সম্পন্ন করুন
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>
        </form>
    </div>
</div>

<style>
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-bounce-slow { animation: bounce-slow 3s ease-in-out infinite; }
</style>
@endsection