@extends('layouts.employer')

@section('content')
<div class="bg-slate-50 min-h-screen pb-24" style="font-family: 'Hind Siliguri', sans-serif;">
    
    {{-- Header --}}
    <div class="bg-white px-6 pt-10 pb-6 shadow-sm border-b border-slate-100">
        <div class="flex items-center gap-4">
            <a href="{{ route('employer.home') }}" class="text-slate-400">
                <i class="fa-solid fa-arrow-left text-lg"></i>
            </a>
            <h1 class="text-xl font-bold text-slate-800">কাজের ইতিহাস</h1>
        </div>
    </div>

    <div class="p-4 space-y-4">
        @forelse($applications as $app)
            {{-- History Card --}}
            <div class="bg-white rounded-[2rem] p-5 shadow-sm border border-slate-100 relative">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 overflow-hidden border border-slate-50">
                            <img src="{{ $app->worker->profile_photo ? asset('storage/'.$app->worker->profile_photo) : 'https://ui-avatars.com/api/?name='.$app->worker->name }}" alt="worker">
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-800">{{ $app->worker->name }}</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">
                                {{ $app->job->title }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="bg-blue-50 text-blue-600 text-[9px] font-black uppercase px-2 py-1 rounded-lg">
                            Completed
                        </span>
                        <p class="text-[10px] text-slate-400 font-bold mt-1">
                            {{ \Carbon\Carbon::parse($app->completed_at)->format('d M, Y') }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-between bg-slate-50 rounded-2xl p-4 border border-slate-100/50">
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">পেমেন্ট স্ট্যাটাস</p>
                        <p class="text-sm font-black text-emerald-600">৳{{ $app->job->wage }} (Paid)</p>
                    </div>
                    
                    {{-- রেটিং দেওয়ার বাটন (যদি আগে রেটিং না দেওয়া থাকে) --}}
                    @if(!$app->worker->reviews()->where('job_id', $app->job_id)->exists())
                        <button onclick="openReviewModal('{{ $app->id }}')" class="bg-slate-800 text-white text-[10px] font-black px-4 py-2 rounded-xl shadow-lg active:scale-95 transition-all">
                            রিভিউ দিন
                        </button>
                    @else
                        <div class="flex items-center gap-1 text-orange-400">
                            <i class="fa-solid fa-star text-xs"></i>
                            <span class="text-xs font-black text-slate-800">Reviewed</span>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center text-slate-300 mb-4">
                    <i class="fa-solid fa-clock-rotate-left text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">কোনো ইতিহাস নেই</h3>
                <p class="text-slate-400 text-sm px-10">আপনার সম্পন্ন করা কাজগুলো এখানে জমা থাকবে।</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Review Modal (Optional - JS দিয়ে হ্যান্ডেল করতে পারেন) --}}
<script>
    function openReviewModal(appId) {
        // এখানে একটি সিম্পল প্রম্পট বা আপনার কাস্টম মডাল ওপেন করতে পারেন
        let rating = prompt("১ থেকে ৫ এর মধ্যে রেটিং দিন (যেমন: ৫):", "5");
        let comment = prompt("আপনার মন্তব্য লিখুন:", "খুব ভালো কাজ করেছেন।");
        
        if (rating) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = `/employer/application/${appId}/review`;
            form.innerHTML = `
                @csrf
                <input type="hidden" name="rating" value="${rating}">
                <input type="hidden" name="comment" value="${comment}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
@endsection