@extends('layouts.employer')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&display=swap" rel="stylesheet">

<div class="px-6 pt-12 pb-32 max-w-lg mx-auto bg-[#f8fafc] min-h-screen relative" x-data="{ editMode: false }" style="font-family: 'Hind Siliguri', sans-serif;">
    
    {{-- Floating Edit/Settings Button --}}
    <div class="absolute top-8 right-6 z-20">
        <button @click="editMode = !editMode" 
                class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-500 shadow-xl"
                :class="editMode ? 'bg-rose-500 text-white rotate-90' : 'bg-white text-slate-800 shadow-slate-200'">
            <i class="fa-solid" :class="editMode ? 'fa-xmark' : 'fa-pencil-square text-xl'"></i>
        </button>
    </div>

    {{-- View Mode --}}
    <div x-show="!editMode" x-transition:enter="transition duration-500" x-transition:enter-start="opacity-0 scale-95">
        
        {{-- Hero Section --}}
        <div class="text-center mb-10 pt-4">
            <div class="relative inline-block">
                {{-- Verified Animation Ring --}}
                @if(Auth::user()->is_verified)
                    <div class="absolute -inset-2 bg-gradient-to-tr from-indigo-500 via-purple-500 to-emerald-500 rounded-[3rem] animate-spin-slow opacity-70 blur-md"></div>
                @endif
                
                <div class="relative bg-white p-1 rounded-[2.8rem]">
                    <img src="{{ Auth::user()->profile_photo ? asset('storage/'.Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=1e293b&color=fff' }}" 
                         class="w-32 h-32 rounded-[2.5rem] object-cover shadow-sm">
                </div>

                @if(Auth::user()->is_verified)
                    <div class="absolute -bottom-2 -right-2 bg-indigo-600 text-white w-8 h-8 rounded-full border-4 border-[#f8fafc] flex items-center justify-center shadow-lg z-10">
                        <i class="fa-solid fa-check text-[10px]"></i>
                    </div>
                @endif
            </div>
            
            <h2 class="text-2xl font-black text-slate-800 mt-6 mb-1">{{ Auth::user()->name }}</h2>
            <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">{{ Auth::user()->role }} Account</p>
        </div>

        {{-- Stats Card --}}
        <div class="bg-white rounded-[2.5rem] p-6 shadow-xl shadow-slate-200/50 border border-white flex justify-around mb-8">
            <div class="text-center">
                <p class="text-[10px] font-black text-slate-300 uppercase mb-1">মোট পোস্ট</p>
                <p class="text-xl font-black text-slate-800">{{ Auth::user()->postedJobs()->count() }}</p>
            </div>
            <div class="w-[1px] bg-slate-100"></div>
            <div class="text-center">
                <p class="text-[10px] font-black text-slate-300 uppercase mb-1">ব্যালেন্স</p>
                <p class="text-xl font-black text-indigo-600">৳{{ number_format(Auth::user()->balance, 0) }}</p>
            </div>
        </div>

        {{-- Details --}}
        <div class="space-y-4 mb-10">
            <div class="bg-white/60 backdrop-blur-md p-5 rounded-3xl border border-white shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 bg-indigo-50 text-indigo-500 rounded-xl flex items-center justify-center shrink-0">
                    <i class="fa-regular fa-envelope"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Email</p>
                    <p class="text-sm font-bold text-slate-700">{{ Auth::user()->email ?? 'Not set' }}</p>
                </div>
            </div>

            <div class="bg-white/60 backdrop-blur-md p-5 rounded-3xl border border-white shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-phone-flip text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Phone</p>
                    <p class="text-sm font-bold text-slate-700">{{ Auth::user()->phone }}</p>
                </div>
            </div>
        </div>

        {{-- Logout Section --}}
        <div class="pt-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-rose-50 text-rose-500 py-5 rounded-[2rem] font-black text-sm uppercase tracking-widest flex items-center justify-center gap-3 border border-rose-100 hover:bg-rose-100 transition-all active:scale-95">
                    <i class="fa-solid fa-power-off"></i> লগআউট করুন
                </button>
            </form>
        </div>
    </div>

    {{-- Edit Mode --}}
    <div x-show="editMode" x-cloak x-transition:enter="transition duration-500" x-transition:enter-start="opacity-0 translate-y-8">
        <form action="{{ route('employer.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-[3rem] p-8 shadow-xl border border-white text-center">
                <div class="relative inline-block">
                    <img id="profile-preview" src="{{ Auth::user()->profile_photo ? asset('storage/'.Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=1e293b&color=fff' }}" 
                         class="w-32 h-32 rounded-[2.5rem] mx-auto object-cover border-4 border-slate-50 shadow-inner">
                    <label for="profile_photo" class="absolute -bottom-2 -right-2 bg-slate-900 text-white w-10 h-10 rounded-2xl flex items-center justify-center cursor-pointer shadow-lg hover:bg-indigo-600">
                        <i class="fa-solid fa-camera text-sm"></i>
                    </label>
                </div>
                <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                <p class="text-[10px] font-black text-slate-400 mt-4 uppercase">ছবি পরিবর্তন করুন</p>
            </div>

            <div class="space-y-4">
                <input type="text" name="name" value="{{ Auth::user()->name }}" placeholder="আপনার নাম" class="w-full p-5 rounded-[1.8rem] border-none bg-white shadow-sm focus:ring-2 focus:ring-indigo-500 font-bold text-slate-700">
                <input type="email" name="email" value="{{ Auth::user()->email }}" placeholder="ইমেইল ঠিকানা" class="w-full p-5 rounded-[1.8rem] border-none bg-white shadow-sm focus:ring-2 focus:ring-indigo-500 font-bold text-slate-700">
                <textarea name="address" rows="3" placeholder="ঠিকানা লিখুন" class="w-full p-5 rounded-[1.8rem] border-none bg-white shadow-sm focus:ring-2 focus:ring-indigo-500 font-bold text-slate-700">{{ Auth::user()->address }}</textarea>
            </div>

            <button type="submit" class="w-full bg-slate-900 text-white py-6 rounded-[2.2rem] font-black text-lg shadow-xl active:scale-95 transition-all">
                সেভ করুন
            </button>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
    
    /* Verified Ring Animation */
    .animate-spin-slow {
        animation: spin 6s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Input Focus Smoothness */
    input, textarea { outline: none !important; transition: all 0.3s ease; }
</style>
@endsection