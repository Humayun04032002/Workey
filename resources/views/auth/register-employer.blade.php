@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 px-6">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="text-center text-3xl font-black text-slate-800">মালিকের তথ্য</h2>
        <p class="mt-2 text-center text-sm text-slate-500">আপনার প্রতিষ্ঠানের বা ব্যক্তিগত তথ্য প্রদান করুন</p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-10 shadow-2xl rounded-[2.5rem] border border-slate-100">
            <form action="{{ route('register.complete') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700">প্রতিষ্ঠানের নাম / আপনার নাম</label>
                        <input type="text" name="business_name" value="{{ old('business_name') }}" required 
                            class="mt-1 block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all">
                        @error('business_name')
                            <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700">এলাকা/লোকেশন</label>
                        <input type="text" name="address" value="{{ old('address') }}" required placeholder="উদা: বনানী, ঢাকা"
                            class="mt-1 block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all">
                        @error('address')
                            <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700">মূল কাজের ধরন</label>
                        <select name="business_type" class="mt-1 block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none appearance-none">
                            <option value="construction">কনস্ট্রাকশন</option>
                            <option value="delivery">ডেলিভারি</option>
                            <option value="cleaning">ক্লিনিং/পরিচ্ছন্নতা</option>
                            <option value="other">অন্যান্য</option>
                        </select>
                    </div>

                    <button type="submit" 
                        class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-sm text-sm font-black text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all active:scale-95">
                        নিবন্ধন সম্পন্ন করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection