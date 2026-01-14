@extends('layouts.employer')

@section('content')
<div class="bg-slate-50 min-h-screen pb-24">
    {{-- হেডার সেকশন --}}
    <div class="bg-white px-6 pt-8 pb-6 rounded-b-[3rem] shadow-sm border-b border-slate-100 mb-6">
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">আবেদনকারীগণ</h1>
        <p class="text-slate-400 text-sm font-bold mt-1">কাজের অগ্রগতি তদারকি ও রিভিউ প্রদান করুন</p>
    </div>

    <div class="px-6">
        @if(session('success'))
            <div class="bg-emerald-500 text-white p-4 rounded-2xl mb-4 font-bold text-sm shadow-lg flex items-center gap-3 animate-bounce-short">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @forelse($applications as $app)
            <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-slate-100 mb-4 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-2 h-full 
                    {{ $app->status === 'completed' ? 'bg-indigo-500' : ($app->status === 'accepted' ? 'bg-emerald-500' : ($app->status === 'rejected' ? 'bg-rose-500' : 'bg-slate-100')) }}">
                </div>

                <div class="flex items-center gap-4 mb-4">
                    <img src="{{ $app->worker->profile_photo ? asset('storage/'.$app->worker->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode($app->worker->name) }}" 
                         class="w-14 h-14 rounded-2xl object-cover border-2 border-indigo-50">
                    
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <h3 class="font-black text-slate-800">{{ $app->worker->name }}</h3>
                            <span class="px-2 py-0.5 rounded-lg text-[9px] font-black uppercase 
                                {{ $app->status === 'completed' ? 'bg-indigo-100 text-indigo-600' : ($app->status === 'accepted' ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500') }}">
                                {{ $app->status }}
                            </span>
                        </div>
                        <p class="text-[10px] font-bold text-indigo-500 uppercase mt-1">
                            <i class="fa-solid fa-briefcase mr-1"></i> {{ $app->job->title }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-black text-slate-800">৳{{ number_format($app->job->wage) }}</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-50 space-y-3">
                    @if($app->status === 'pending')
                        <div class="grid grid-cols-2 gap-3">
                            <form action="{{ route('employer.app.status', ['id' => $app->id, 'status' => 'accepted']) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-emerald-500 text-white py-3 rounded-2xl text-xs font-black">Accept</button>
                            </form>
                            <form action="{{ route('employer.app.status', ['id' => $app->id, 'status' => 'rejected']) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-rose-500 text-white py-3 rounded-2xl text-xs font-black">Reject</button>
                            </form>
                        </div>

                    @elseif($app->status === 'accepted')
                        <form action="{{ route('employer.app.complete', $app->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-2xl text-xs font-black shadow-lg shadow-indigo-100">
                                কাজ সম্পন্ন নিশ্চিত করুন
                            </button>
                        </form>

                    @elseif($app->status === 'completed')
                        @php
                            // আরও শক্তিশালী কুয়েরি লজিক
                            $checkReview = \Illuminate\Support\Facades\DB::table('reviews')
                                ->where('job_id', (int)$app->job_id)
                                ->where('worker_id', (int)$app->worker_id)
                                ->where('employer_id', (int)auth()->id())
                                ->exists();
                        @endphp

                        @if(!$checkReview)
                            <button onclick="openReviewModal('{{ $app->id }}', '{{ $app->worker->name }}')" class="w-full bg-amber-500 text-white py-4 rounded-2xl text-xs font-black shadow-lg shadow-amber-100">
                                <i class="fa-solid fa-star mr-2"></i> কর্মীকে রিভিউ দিন
                            </button>
                        @else
                            <div class="w-full py-4 bg-slate-50 text-emerald-600 rounded-2xl text-center text-[11px] font-black border border-emerald-100 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-circle-check text-sm"></i> 
                                কাজ ও রিভিউ সম্পন্ন হয়েছে
                            </div>
                        @endif
                    @endif

                    <div class="grid grid-cols-2 gap-3">
                        <a href="tel:{{ $app->worker->phone }}" class="flex items-center justify-center gap-2 bg-white text-slate-600 py-3 rounded-2xl text-xs font-black border border-slate-100">
                            <i class="fa-solid fa-phone"></i> কল করুন
                        </a>
                        <a href="{{ route('employer.worker.profile', $app->worker_id) }}" class="flex items-center justify-center gap-2 bg-white text-slate-600 py-3 rounded-2xl text-xs font-black border border-slate-100">
                            <i class="fa-solid fa-user"></i> প্রোফাইল
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-[3rem] p-16 text-center border-2 border-dashed border-slate-200 mt-10">
                <i class="fa-solid fa-user-slash text-3xl text-slate-200 mb-4"></i>
                <h3 class="text-slate-800 font-black text-lg">কোন আবেদন নেই</h3>
            </div>
        @endforelse
    </div>
</div>

{{-- --- উন্নত রিভিউ মডাল --- --}}
<div id="reviewModal" class="fixed inset-0 z-[150] hidden flex items-center justify-center px-6">
    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-md" onclick="closeReviewModal()"></div>
    
    <div class="bg-white w-full max-w-sm rounded-[2.5rem] p-6 shadow-2xl relative transform scale-90 opacity-0 transition-all duration-300" id="modalContent">
        <button onclick="closeReviewModal()" class="absolute -top-12 right-0 text-white text-2xl">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-amber-100 text-amber-500 rounded-3xl flex items-center justify-center mx-auto mb-4 text-2xl shadow-inner">
                <i class="fa-solid fa-star"></i>
            </div>
            <h2 class="text-xl font-black text-slate-800">রিভিউ প্রদান</h2>
            <p class="text-slate-400 text-[11px] font-bold mt-1 uppercase tracking-wider" id="workerNameDisplay"></p>
        </div>

        <form id="reviewForm" method="POST">
            @csrf
            <div class="flex flex-row-reverse justify-center gap-2 mb-6 star-rating">
                @for($i=5; $i>=1; $i--)
                    <input type="radio" name="rating" value="{{ $i }}" id="modalStar{{ $i }}" class="hidden peer" required>
                    <label for="modalStar{{ $i }}" class="cursor-pointer text-slate-200 peer-hover:text-amber-400 peer-checked:text-amber-500 transition-all transform hover:scale-110">
                        <i class="fa-solid fa-star text-4xl"></i>
                    </label>
                @endfor
            </div>

            <div class="mb-4">
                <p class="text-[9px] font-black text-slate-400 uppercase mb-2 ml-1">দ্রুত মন্তব্য বাছাই করুন</p>
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="setComment('অসাধারণ এবং সুন্দর কাজ!')" class="bg-slate-50 hover:bg-indigo-50 hover:text-indigo-600 text-slate-500 px-3 py-2 rounded-xl text-[10px] font-bold border border-slate-100 transition-all">✨ সুন্দর কাজ</button>
                    <button type="button" onclick="setComment('খুবই প্রফেশনাল আচরণ।')" class="bg-slate-50 hover:bg-indigo-50 hover:text-indigo-600 text-slate-500 px-3 py-2 rounded-xl text-[10px] font-bold border border-slate-100 transition-all">👔 প্রফেশনাল</button>
                    <button type="button" onclick="setComment('সময়ে কাজ শেষ করেছেন। ধন্যবাদ।')" class="bg-slate-50 hover:bg-indigo-50 hover:text-indigo-600 text-slate-500 px-3 py-2 rounded-xl text-[10px] font-bold border border-slate-100 transition-all">⏰ সময়নিষ্ঠ</button>
                </div>
            </div>

            <div class="mb-6">
                <textarea name="comment" id="commentBox" rows="3" 
                    class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 text-sm font-bold text-slate-700 focus:ring-0 focus:border-amber-400 placeholder-slate-300 transition-all" 
                    placeholder="আপনার অভিজ্ঞতা লিখুন..."></textarea>
            </div>

            <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-slate-200 active:scale-95 transition-all">
                রিভিউ জমা দিন
            </button>
        </form>
    </div>
</div>

<style>
    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input:checked ~ label {
        color: #f59e0b !important;
        text-shadow: 0 0 10px rgba(245, 158, 11, 0.3);
    }
    @keyframes bounce-short {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-4px); }
    }
    .animate-bounce-short {
        animation: bounce-short 0.5s ease-in-out 1;
    }
</style>

<script>
    function openReviewModal(appId, workerName) {
        const modal = document.getElementById('reviewModal');
        const modalContent = document.getElementById('modalContent');
        const form = document.getElementById('reviewForm');
        const nameDisplay = document.getElementById('workerNameDisplay');

        // স্লাশ ঠিকমতো আছে কি না চেক করুন আপনার রাউট অনুযায়ী
        form.action = `/employer/application/${appId}/review`;
        nameDisplay.innerText = workerName;

        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-90', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeReviewModal() {
        const modal = document.getElementById('reviewModal');
        const modalContent = document.getElementById('modalContent');
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-90', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.getElementById('reviewForm').reset();
        }, 300);
    }

    function setComment(text) {
        document.getElementById('commentBox').value = text;
    }
</script>
@endsection