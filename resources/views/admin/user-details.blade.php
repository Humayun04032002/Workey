@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto pb-20">
    {{-- Back Button & Title --}}
    <div class="mb-8 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users') }}" class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm border border-slate-100 text-slate-600 hover:bg-slate-50">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-800">ইউজার প্রোফাইল ডিটেইলস</h1>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">User ID: #{{ $user->id }}</p>
            </div>
        </div>
        
        <form action="{{ route('admin.users.toggle-ban', $user->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত?')">
            @csrf
            @method('PATCH')
            <button type="submit" class="px-6 py-3 {{ $user->is_banned ? 'bg-emerald-500' : 'bg-rose-500' }} text-white rounded-2xl font-black shadow-lg hover:scale-105 transition-transform">
                <i class="fa-solid {{ $user->is_banned ? 'fa-user-check' : 'fa-user-slash' }} mr-2"></i>
                {{ $user->is_banned ? 'আন-ব্যান ইউজার' : 'ইউজার ব্যান করুন' }}
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column: User Info Card --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 text-center">
                <div class="relative w-32 h-32 mx-auto mb-4">
                    <img src="{{ $user->profile_photo ? asset('storage/'.$user->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&size=128&background=random' }}" 
                         class="w-full h-full rounded-[2.5rem] object-cover border-4 border-slate-50 shadow-inner">
                    @if($user->is_verified)
                        <div class="absolute -bottom-2 -right-2 bg-blue-500 text-white w-8 h-8 rounded-full flex items-center justify-center border-4 border-white">
                            <i class="fa-solid fa-check text-xs"></i>
                        </div>
                    @endif
                </div>
                <h2 class="text-xl font-black text-slate-800">{{ $user->name }}</h2>
                <p class="text-indigo-600 font-bold text-sm mb-6">{{ ucfirst($user->role) }}</p>
                
                <div class="grid grid-cols-2 gap-4 border-t border-slate-50 pt-6">
                    <div class="text-left">
                        <p class="text-[10px] text-slate-400 font-black uppercase">ব্যালেন্স</p>
                        <p class="text-lg font-black text-slate-800">৳{{ number_format($user->balance, 2) }}</p>
                    </div>
                    <div class="text-left border-l border-slate-50 pl-4">
                        <p class="text-[10px] text-slate-400 font-black uppercase">রেটিং</p>
                        <div class="flex items-center gap-1 text-amber-500 font-black">
                            <i class="fa-solid fa-star text-xs"></i>
                            <span>{{ number_format($user->rating, 1) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact Info --}}
            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white">
                <h3 class="font-black mb-4 text-slate-400 uppercase text-xs tracking-widest">যোগাযোগের তথ্য</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-phone text-slate-500 w-5"></i>
                        <span class="font-bold text-sm">{{ $user->phone }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-envelope text-slate-500 w-5"></i>
                        <span class="font-bold text-sm">{{ $user->email ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-location-dot text-slate-500 w-5"></i>
                        <span class="font-bold text-sm text-slate-300">{{ $user->address ?? 'ঠিকানা দেওয়া হয়নি' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Activity & Reports --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <p class="text-slate-400 text-[10px] font-black uppercase mb-1">মোট কাজ</p>
                    <p class="text-2xl font-black text-slate-800">
                        {{ $user->role == 'worker' ? $user->jobs_as_worker_count : $user->posted_jobs_count }}
                    </p>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <p class="text-slate-400 text-[10px] font-black uppercase mb-1">মোট রিপোর্ট</p>
                    <p class="text-2xl font-black {{ $user->reports_received_count > 0 ? 'text-rose-500' : 'text-slate-800' }}">
                        {{ $user->reports_received_count }}
                    </p>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm col-span-2 md:col-span-1">
                    <p class="text-slate-400 text-[10px] font-black uppercase mb-1">সদস্য হয়েছেন</p>
                    <p class="text-sm font-black text-slate-800">{{ $user->created_at->format('d M, Y') }}</p>
                </div>
            </div>

            {{-- Reports Table --}}
            <div class="bg-white rounded-[2.5rem] border border-rose-100 shadow-sm overflow-hidden">
                <div class="p-6 bg-rose-50/50 border-b border-rose-50 flex items-center gap-2 text-rose-600">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <h3 class="font-black uppercase text-sm tracking-wider">ইউজারের বিরুদ্ধে রিপোর্টসমূহ</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50">
                            <tr class="text-[10px] font-black text-slate-400 uppercase">
                                <th class="p-6">রিপোর্টার</th>
                                <th class="p-6">কারণ / অভিযোগ</th>
                                <th class="p-6 text-right">তারিখ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($user->reportsReceived as $report)
                            <tr>
                                <td class="p-6 flex items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($report->reporter->name) }}&background=random" class="w-8 h-8 rounded-lg">
                                    <span class="font-bold text-slate-700">{{ $report->reporter->name }}</span>
                                </td>
                                <td class="p-6">
                                    <p class="text-sm text-slate-600 font-medium leading-relaxed italic">"{{ $report->reason }}"</p>
                                </td>
                                <td class="p-6 text-right text-xs text-slate-400 font-bold">
                                    {{ $report->created_at->diffForHumans() }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="p-10 text-center text-slate-400 font-bold italic">এখনো কোনো রিপোর্ট জমা পড়েনি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection