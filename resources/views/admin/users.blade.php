@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto pb-20">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800">ইউজার ম্যানেজমেন্ট</h1>
            <p class="text-sm text-slate-500 font-medium">সিস্টেমের সকল কর্মী এবং নিয়োগকর্তাদের তালিকা ও নিয়ন্ত্রণ</p>
        </div>
        <form action="{{ route('admin.users') }}" method="GET" class="flex gap-2 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="নাম বা ফোন নম্বর..." class="w-full md:w-64 px-4 py-2 rounded-xl border-none shadow-sm focus:ring-2 focus:ring-indigo-500">
            <button class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-indigo-700 transition-all">খুঁজুন</button>
        </form>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm overflow-hidden border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50">
                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="p-6">ইউজার প্রোফাইল</th>
                        <th class="p-6 text-center">রোল</th>
                        <th class="p-6 text-center">অ্যাক্টিভিটি</th>
                        <th class="p-6 text-center">রিপোর্টস</th>
                        <th class="p-6">স্ট্যাটাস</th>
                        <th class="p-6 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm font-bold text-slate-600">
                    @foreach($users as $user)
                    <tr class="hover:bg-slate-50/50 transition-colors {{ $user->is_banned ? 'bg-rose-50/30' : '' }}">
                        <td class="p-6 flex items-center gap-3">
                            <div class="relative">
                                <img src="{{ $user->profile_photo ? asset('storage/'.$user->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" class="w-12 h-12 rounded-2xl object-cover border border-slate-100 shadow-sm">
                                @if($user->is_online) <div class="absolute -top-1 -right-1 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></div> @endif
                            </div>
                            <div>
                                <p class="font-black text-slate-800 tracking-tight">{{ $user->name }}</p>
                                <p class="text-[10px] text-slate-400 italic tracking-wider">{{ $user->phone }}</p>
                            </div>
                        </td>
                        <td class="p-6 text-center">
                            <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase {{ $user->role == 'worker' ? 'bg-indigo-50 text-indigo-600' : 'bg-amber-50 text-amber-600' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="p-6 text-center">
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] text-slate-400 uppercase tracking-tighter">কাজ: {{ $user->role == 'worker' ? $user->completed_jobs_count : $user->posted_jobs_count }}টি</span>
                                <div class="flex items-center justify-center gap-1 text-amber-500">
                                    <i class="fa-solid fa-star text-[10px]"></i>
                                    <span class="text-xs">{{ number_format($user->rating, 1) }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-6 text-center">
                            @if($user->reports_received_count > 0)
                                <span class="bg-rose-500 text-white px-2 py-0.5 rounded text-[10px] animate-bounce">{{ $user->reports_received_count }}টি রিপোর্ট</span>
                            @else
                                <span class="text-slate-300 text-[10px]">০</span>
                            @endif
                        </td>
                        <td class="p-6">
                            @if($user->is_banned)
                                <span class="bg-rose-100 text-rose-600 px-3 py-1 rounded-full text-[9px] font-black uppercase">Suspended</span>
                            @elseif($user->verification_status == 'verified')
                                <span class="bg-emerald-100 text-emerald-600 px-3 py-1 rounded-full text-[9px] font-black uppercase">Verified</span>
                            @else
                                <span class="bg-slate-100 text-slate-400 px-3 py-1 rounded-full text-[9px] font-black uppercase">Active</span>
                            @endif
                        </td>
                        <td class="p-6 text-right">
                            <div class="flex justify-end gap-2">
                                {{-- ডিটেইলস বাটন --}}
                                <a href="{{ route('admin.users.show', $user->id) }}" class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                                
                                {{-- ব্যান/আনব্যান বাটন --}}
                                <form action="{{ route('admin.users.toggle-ban', $user->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-8 h-8 {{ $user->is_banned ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} rounded-lg flex items-center justify-center hover:scale-110 transition-transform">
                                        <i class="fa-solid {{ $user->is_banned ? 'fa-user-check' : 'fa-user-slash' }} text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection