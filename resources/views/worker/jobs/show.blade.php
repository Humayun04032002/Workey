<div class="flex gap-4">
    <a href="https://www.google.com/maps/dir/?api=1&destination=${job.lat},{{ $job->lng }}" target="_blank" class="w-16 h-16 bg-slate-100 rounded-3xl flex items-center justify-center text-xl">
        <i class="fa-solid fa-diamond-turn-right"></i>
    </a>

    @if(auth()->user()->balance < 10)
        <a href="{{ route('worker.wallet') }}" class="flex-1 h-16 bg-rose-500 text-white rounded-3xl flex items-center justify-center font-black text-lg shadow-xl shadow-rose-100">
            ব্যালেন্স নেই, রিচার্জ করুন
        </a>
    @else
        <form action="{{ route('worker.apply', $job->id) }}" method="POST" class="flex-1" onsubmit="return confirm('এই আবেদনে আপনার ওয়ালেট থেকে ১০ টাকা কাটা হবে। নিশ্চিত?')">
            @csrf
            <button type="submit" class="w-full h-16 bg-indigo-600 text-white rounded-3xl font-black text-lg shadow-xl shadow-indigo-100">
                আবেদন করুন (৳১০)
            </button>
        </form>
    @endif
</div>