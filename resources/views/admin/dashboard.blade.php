@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto pb-10 px-4">
    {{-- হেডার সেকশন --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">অ্যাডমিন কন্ট্রোল সেন্টার</h1>
            <p class="text-slate-500 font-bold mt-1 italic">Workey প্ল্যাটফর্মের ব্যাকবোন আজ সচল আছে।</p>
        </div>
        <div class="flex gap-3">
            <div class="bg-white px-5 py-3 rounded-2xl shadow-sm border border-slate-100 font-black text-indigo-600 flex items-center gap-2">
                <i class="fa-solid fa-calendar-day"></i>
                {{ now()->format('d M, Y') }}
            </div>
            <button onclick="location.reload()" class="bg-white p-3 rounded-2xl shadow-sm border border-slate-100 text-slate-400 hover:text-indigo-600 transition-all">
                <i class="fa-solid fa-arrows-rotate"></i>
            </button>
        </div>
    </div>

    {{-- মূল স্ট্যাটাস কার্ডসমূহ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
        {{-- রেভিনিউ কার্ড --}}
        <a href="{{ route('admin.revenue') }}" class="bg-slate-900 p-6 rounded-[2.5rem] text-white shadow-xl shadow-slate-200 relative overflow-hidden group block">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-500/10 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-indigo-500 rounded-xl">
                        <i class="fa-solid fa-sack-dollar text-xl text-white"></i>
                    </div>
                    <span class="text-[9px] bg-white/10 px-2 py-1 rounded-full font-black tracking-widest uppercase">Revenue</span>
                </div>
                <p class="text-3xl font-black tracking-tighter">৳{{ number_format($stats['total_revenue']) }}</p>
                <p class="text-[11px] mt-2 font-bold text-emerald-400">
                    <i class="fa-solid fa-arrow-trend-up mr-1"></i> আজকের আয়: ৳{{ number_format($stats['today_income']) }}
                </p>
            </div>
        </a>

        {{-- কর্মী কার্ড --}}
        <a href="{{ route('admin.users') }}?role=worker" class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-md transition-shadow block">
            <i class="fa-solid fa-users-gear text-emerald-500 text-2xl mb-4"></i>
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-wider">মোট কর্মী (Worker)</p>
            <p class="text-3xl font-black text-slate-800">{{ $stats['total_workers'] }}</p>
        </a>

        {{-- মালিক কার্ড --}}
        <a href="{{ route('admin.users') }}?role=employer" class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-md transition-shadow block">
            <i class="fa-solid fa-user-tie text-amber-500 text-2xl mb-4"></i>
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-wider">মোট মালিক (Employer)</p>
            <p class="text-3xl font-black text-slate-800">{{ $stats['total_employers'] }}</p>
        </a>

        {{-- পেন্ডিং ভেরিফিকেশন --}}
        <a href="{{ route('admin.verifications') }}" class="bg-rose-50 p-6 rounded-[2.5rem] border border-rose-100 shadow-sm group block">
            <div class="flex justify-between items-center mb-4">
                <i class="fa-solid fa-id-card text-rose-500 text-2xl"></i>
                @if($stats['pending_verifications'] > 0)
                    <span class="flex h-3 w-3 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                    </span>
                @endif
            </div>
            <p class="text-rose-400 text-[10px] font-black uppercase tracking-wider">পেন্ডিং ভেরিফিকেশন</p>
            <p class="text-3xl font-black text-rose-600">{{ $stats['pending_verifications'] }}</p>
        </a>
    </div>

    {{-- গ্রাফ এবং কুইক সেটিংস --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        {{-- পারফরম্যান্স গ্রাফ --}}
        <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-black text-slate-800 text-xl italic">সাপ্তাহিক পারফরম্যান্স</h2>
                <select class="text-xs font-bold bg-slate-50 border-none rounded-xl focus:ring-0">
                    <option>গত ৭ দিন</option>
                    <option>গত ৩০ দিন</option>
                </select>
            </div>
            <div class="h-64 flex items-end gap-3 px-2">
                @foreach([40, 70, 45, 90, 65, 85, 100] as $height)
                    <div class="flex-1 bg-indigo-100 rounded-t-xl hover:bg-indigo-500 transition-colors cursor-pointer relative group" style="height: {{ $height }}%">
                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded hidden group-hover:block">{{ $height }}%</span>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between mt-4 px-2 text-[10px] font-black text-slate-400 uppercase">
                <span>Sat</span><span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span>
            </div>
        </div>

        {{-- সিস্টেম সেটিংস --}}
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <h2 class="font-black text-slate-800 mb-6 uppercase text-sm tracking-widest">ব্যাকবোন সেটিংস</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl">
                    <div>
                        <p class="text-xs font-black text-slate-700">এপ্লাই ফি (Apply Fee)</p>
                        <p class="text-[10px] font-bold text-slate-400 italic">সিস্টেম ওয়াইড ফিক্সড</p>
                    </div>
                    <span class="font-black text-indigo-600">৳২০</span>
                </div>
                <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl text-slate-400">
                    <div>
                        <p class="text-xs font-black">মেইনটেন্যান্স মোড</p>
                        <p class="text-[10px] font-bold italic">অফ করা আছে</p>
                    </div>
                    <i class="fa-solid fa-toggle-off text-xl"></i>
                </div>
                <a href="{{ route('admin.settings') }}" class="block w-full py-4 bg-slate-800 text-white text-center rounded-2xl font-black text-xs hover:bg-black transition-all">
                    সিস্টেম কনফিগারেশন আপডেট করুন
                </a>
            </div>
        </div>
    </div>

    {{-- সাম্প্রতিক সচল কাজ এবং নতুন ইউজার --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- সচল কাজ টেবিল --}}
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                <h2 class="font-black text-slate-800">সাম্প্রতিক কাজ (Active Control)</h2>
                <a href="{{ route('admin.jobs') }}" class="text-[10px] font-black text-indigo-600 bg-indigo-50 px-4 py-2 rounded-full uppercase tracking-tighter">সব সচল কাজ দেখুন</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="p-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">কাজের নাম</th>
                            <th class="p-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">মালিক</th>
                            <th class="p-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">একশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($latest_jobs as $job)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-4">
                                <p class="text-sm font-black text-slate-700">{{ $job->title }}</p>
                                <p class="text-[9px] font-bold text-indigo-500 uppercase">{{ $job->category }}</p>
                            </td>
                            <td class="p-4 text-sm text-slate-500 font-bold italic">{{ $job->employer->name ?? 'User' }}</td>
                            <td class="p-4 text-center">
                                <form action="{{ route('admin.job.action', $job->id) }}" method="POST">
                                    @csrf
                                    <button class="text-slate-300 hover:text-rose-500 transition-colors">
                                        <i class="fa-solid fa-circle-minus"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-8 text-center text-slate-400 font-bold italic">কোন সচল কাজ পাওয়া যায়নি।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- নতুন ইউজার লিস্ট --}}
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-6">
            <h2 class="font-black text-slate-800 mb-6 flex justify-between items-center">
                নতুন ইউজার
                <span class="text-[9px] bg-emerald-100 text-emerald-600 px-2 py-1 rounded-md uppercase tracking-widest font-black">Live</span>
            </h2>
            <div class="space-y-6">
                @foreach($recent_activities as $user)
                <a href="{{ route('admin.user.details', $user->id) }}" class="flex items-center gap-4 group cursor-pointer block">
                    <div class="relative">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff" class="w-11 h-11 rounded-2xl shadow-sm group-hover:scale-105 transition-transform">
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-white rounded-full flex items-center justify-center shadow-sm">
                            <div class="w-2 h-2 rounded-full {{ $user->role === 'worker' ? 'bg-emerald-500' : 'bg-amber-500' }}"></div>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-black text-slate-800 leading-tight group-hover:text-indigo-600 transition-colors">{{ $user->name }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $user->role }} • {{ $user->created_at->diffForHumans() }}</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-200 group-hover:text-slate-400 transition-colors"></i>
                </a>
                @endforeach
            </div>
            <a href="{{ route('admin.users') }}" class="block w-full mt-8 py-3 bg-slate-50 text-slate-500 text-center rounded-xl font-black text-[10px] uppercase hover:bg-slate-100 transition-all">
                সকল ইউজার ম্যানেজ করুন
            </a>
        </div>
    </div>
</div>

<style>
    body { font-family: 'Inter', sans-serif; }
    .tracking-tighter { letter-spacing: -0.05em; }
</style>
@endsection