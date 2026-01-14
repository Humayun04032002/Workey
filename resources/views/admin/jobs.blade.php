@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800">জব ম্যানেজমেন্ট</h1>
            <p class="text-slate-500 font-bold text-sm">প্ল্যাটফর্মের সকল পোস্ট করা কাজের তালিকা।</p>
        </div>
        <div class="bg-indigo-50 px-4 py-2 rounded-xl border border-indigo-100 font-black text-indigo-600 text-sm">
            মোট জব: {{ $jobs->total() }}
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm overflow-hidden border border-slate-100">
        <table class="w-full text-left">
            <thead class="bg-slate-50">
                <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <th class="p-6">কাজের শিরোনাম ও মালিক</th>
                    <th class="p-6">ক্যাটাগরি</th>
                    <th class="p-6">মজুরি</th>
                    <th class="p-6">অবস্থা (Status)</th>
                    <th class="p-6 text-center">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 text-sm font-bold text-slate-600">
                @foreach($jobs as $job)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="p-6">
                        <div class="font-black text-slate-800">{{ $job->title }}</div>
                        <div class="text-[10px] text-slate-400">মালিক: {{ $job->employer->name ?? 'N/A' }}</div>
                    </td>
                    <td class="p-6 uppercase text-[10px]">
                        {{ $job->category ?? 'General' }}
                    </td>
                    <td class="p-6 text-indigo-600 font-black">
                        ৳{{ number_format($job->wage) }}
                    </td>
                    <td class="p-6">
                        @php
                            $statusColors = [
                                'open' => 'bg-emerald-100 text-emerald-600',
                                'pending' => 'bg-amber-100 text-amber-600',
                                'completed' => 'bg-indigo-100 text-indigo-600',
                                'cancelled' => 'bg-rose-100 text-rose-600',
                            ];
                        @endphp
                        <span class="{{ $statusColors[$job->status] ?? 'bg-slate-100' }} px-3 py-1 rounded-full text-[9px] font-black uppercase">
                            {{ $job->status }}
                        </span>
                    </td>
                    <td class="p-6">
                        <div class="flex justify-center gap-2">
                            {{-- ভিউ বাটন --}}
                            <a href="#" class="p-2 bg-slate-50 text-slate-400 hover:text-indigo-600 rounded-lg transition-colors">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            
                            {{-- ডিজেবল বাটন --}}
                            <form action="{{ route('admin.job.action', [$job->id, 'cancelled']) }}" method="POST" onsubmit="return confirm('আপনি কি এই জবটি বাতিল করতে চান?')">
                                @csrf
                                <button type="submit" class="p-2 bg-rose-50 text-rose-400 hover:text-rose-600 rounded-lg transition-colors">
                                    <i class="fa-solid fa-ban"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $jobs->links() }}
    </div>
</div>
@endsection