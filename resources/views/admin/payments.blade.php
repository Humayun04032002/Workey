@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">ডিপোজিট রিকোয়েস্ট</h1>
            <p class="text-slate-500 font-bold text-sm">ম্যানুয়াল পেমেন্ট ভেরিফিকেশন প্যানেল</p>
        </div>
        <div class="flex gap-4">
            <div class="bg-white px-6 py-2 rounded-2xl border border-slate-100 shadow-sm text-center">
                <span class="text-[10px] font-black text-slate-400 uppercase block">পেন্ডিং</span>
                <span class="text-xl font-black text-amber-600">{{ $deposits->where('status', 'pending')->count() }} টি</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">ইউজার ও মেথড</th>
                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">ট্রানজ্যাকশন আইডি</th>
                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">পরিমাণ</th>
                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">স্ট্যাটাস</th>
                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">একশন</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($deposits as $deposit)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-black">
                                {{ substr($deposit->method, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-black text-slate-700 text-sm">{{ $deposit->user->name ?? 'Unknown' }}</p>
                                <p class="text-[10px] font-bold text-indigo-500 uppercase">{{ $deposit->method }} • {{ $deposit->sender_number }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-mono text-xs font-bold bg-slate-100 px-2 py-1 rounded text-slate-600">
                            {{ $deposit->transaction_id }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-black text-slate-700 text-sm">
                        ৳{{ number_format($deposit->amount, 2) }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase 
                            {{ $deposit->status == 'approved' ? 'bg-emerald-100 text-emerald-600' : 
                               ($deposit->status == 'rejected' ? 'bg-rose-100 text-rose-600' : 'bg-amber-100 text-amber-600') }}">
                            {{ $deposit->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($deposit->status == 'pending')
                        <div class="flex items-center justify-center gap-2">
                            {{-- Approve Button --}}
                            <form action="{{ route('admin.deposit.handle', $deposit->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="p-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-all shadow-sm shadow-emerald-200">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>

                            {{-- Reject Button & Note Input --}}
                            <button onclick="toggleRejectForm({{ $deposit->id }})" class="p-2 bg-rose-500 text-white rounded-lg hover:bg-rose-600 transition-all shadow-sm shadow-rose-200">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        {{-- Hidden Reject Form --}}
                        <div id="reject-form-{{ $deposit->id }}" class="hidden mt-4 p-4 bg-slate-50 rounded-2xl border border-slate-200 absolute right-10 z-50 w-64 shadow-xl">
                            <form action="{{ route('admin.deposit.handle', $deposit->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <label class="text-[10px] font-black text-slate-500 uppercase mb-2 block">রিজেক্ট করার কারণ:</label>
                                <textarea name="admin_note" class="w-full rounded-xl border-slate-200 text-xs p-2 mb-2 focus:ring-rose-500" placeholder="উদা: ভুল ট্রানজ্যাকশন আইডি" required></textarea>
                                <div class="flex gap-2">
                                    <button type="submit" class="flex-1 bg-rose-600 text-white py-2 rounded-lg text-[10px] font-black uppercase">Confirm Reject</button>
                                    <button type="button" onclick="toggleRejectForm({{ $deposit->id }})" class="bg-slate-200 text-slate-600 px-3 py-2 rounded-lg text-[10px] font-black">Cancel</button>
                                </div>
                            </form>
                        </div>
                        @else
                            <p class="text-[10px] font-bold text-slate-400 italic text-center">প্রসেসড অন {{ $deposit->updated_at->format('d M') }}</p>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-12 text-center text-slate-400 font-bold italic">কোন ডিপোজিট রিকোয়েস্ট পাওয়া যায়নি।</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="p-6 bg-slate-50/30">
            {{ $deposits->links() }}
        </div>
    </div>
</div>

<script>
    function toggleRejectForm(id) {
        const form = document.getElementById(`reject-form-${id}`);
        form.classList.toggle('hidden');
    }
</script>
@endsection