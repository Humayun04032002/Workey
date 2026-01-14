@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">ডিপোজিট রিকোয়েস্ট</h1>
            <p class="text-slate-500 font-bold text-sm">ইউজারদের পাঠানো ম্যানুয়াল পেমেন্ট তালিকা</p>
        </div>
        <div class="bg-white px-6 py-2 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[10px] font-black text-slate-400 uppercase block">মোট রিকোয়েস্ট</span>
            <span class="text-xl font-black text-indigo-600">{{ $deposits->total() }} টি</span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-100 text-emerald-700 rounded-2xl font-bold text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-100 text-rose-700 rounded-2xl font-bold text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">ইউজার ও মেথড</th>
                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">ট্রানজ্যাকশন আইডি</th>
                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">পরিমাণ</th>
                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">স্ট্যাটাস</th>
                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($deposits as $deposit)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                                <i class="fa-solid fa-user-tag text-xs"></i>
                            </div>
                            <div>
                                <p class="font-black text-slate-700 text-sm">{{ $deposit->user->name ?? 'Unknown' }}</p>
                                <p class="text-[10px] font-bold text-indigo-500 uppercase">{{ $deposit->method }} • {{ $deposit->sender_number }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-mono text-xs font-bold bg-slate-100 px-2 py-1 rounded text-slate-600 uppercase">
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
                    <td class="px-6 py-4 text-right relative">
                        @if($deposit->status == 'pending')
                        <div class="flex items-center justify-end gap-2">
                            <form action="{{ route('admin.deposit.handle', $deposit->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" onclick="return confirm('আপনি কি নিশ্চিত? ইউজারের ব্যালেন্স যোগ হবে।')" 
                                    class="w-8 h-8 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-all flex items-center justify-center">
                                    <i class="fa-solid fa-check text-xs"></i>
                                </button>
                            </form>

                            <button onclick="toggleRejectForm({{ $deposit->id }})" 
                                class="w-8 h-8 bg-rose-500 text-white rounded-lg hover:bg-rose-600 transition-all flex items-center justify-center">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        </div>

                        <div id="reject-form-{{ $deposit->id }}" class="hidden absolute right-6 top-16 z-50 w-64 p-4 bg-white border border-slate-200 rounded-2xl shadow-2xl">
                            <form action="{{ route('admin.deposit.handle', $deposit->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <label class="text-[10px] font-black text-slate-400 uppercase mb-2 block">রিজেক্ট করার কারণ:</label>
                                <textarea name="admin_note" required class="w-full text-xs p-2 border border-slate-100 rounded-xl mb-3 focus:ring-rose-500 focus:border-rose-500" placeholder="উদা: ভুল ট্রানজ্যাকশন আইডি"></textarea>
                                <div class="flex gap-2">
                                    <button type="submit" class="flex-1 bg-rose-600 text-white py-2 rounded-lg text-[10px] font-black uppercase">Confirm</button>
                                    <button type="button" onclick="toggleRejectForm({{ $deposit->id }})" class="bg-slate-100 text-slate-500 px-3 py-2 rounded-lg text-[10px] font-black uppercase">Cancel</button>
                                </div>
                            </form>
                        </div>
                        @else
                            <div class="text-[10px] font-bold text-slate-400">
                                <p>{{ $deposit->updated_at->format('d M, Y') }}</p>
                                <p>{{ $deposit->updated_at->format('h:i A') }}</p>
                            </div>
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
        
        <div class="p-6 bg-slate-50/30 border-t border-slate-50">
            {{ $deposits->links() }}
        </div>
    </div>
</div>

<script>
    function toggleRejectForm(id) {
        const form = document.getElementById(`reject-form-${id}`);
        // Close other open forms
        document.querySelectorAll('[id^="reject-form-"]').forEach(el => {
            if(el.id !== `reject-form-${id}`) el.classList.add('hidden');
        });
        form.classList.toggle('hidden');
    }
</script>
@endsection