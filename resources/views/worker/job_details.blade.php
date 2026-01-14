@extends('layouts.worker')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

<div class="px-4 pt-4 pb-32 max-w-lg mx-auto bg-white min-h-screen font-sans">
    
    {{-- হেডার --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('worker.home') }}" class="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center text-slate-800">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h2 class="text-xl font-black text-slate-800">কাজের বিস্তারিত</h2>
    </div>

    {{-- এরর এবং সাকসেস মেসেজ --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-4 py-3 rounded-2xl mb-4 font-bold text-sm flex items-center gap-3">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-2xl mb-4 font-bold text-sm flex items-center gap-3">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ session('error') }}
        </div>
    @endif

    <h1 class="text-2xl font-black text-slate-900 mb-1 leading-tight">{{ $job->title }}</h1>
    <div class="flex items-center gap-2 mb-6">
        <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black rounded-full uppercase">{{ $job->category }}</span>
        <p class="text-slate-400 font-bold text-xs">পোস্ট করেছেন: {{ $job->employer->name ?? 'নিয়োগকর্তা' }}</p>
    </div>

    {{-- মূল তথ্য কার্ড --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-50 mb-6 space-y-6">
        
        {{-- মজুরি ও পেমেন্ট মেথড --}}
        <div class="flex items-start justify-between">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 bg-pink-50 rounded-xl flex items-center justify-center text-pink-500 shrink-0">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400">মজুরি</p>
                    <h3 class="text-lg font-black text-slate-800">
                        ৳{{ number_format($job->wage) }} 
                        <span class="text-xs text-slate-400 font-bold">/{{ $job->wage_type == 'hourly' ? 'ঘণ্টা' : 'দিন' }}</span>
                    </h3>
                </div>
            </div>
            
            <div class="text-right">
                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">পেমেন্ট মাধ্যম</p>
                @if($job->payment_type == 'in_app')
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[9px] font-black rounded-lg flex items-center gap-1">
                        <i class="fa-solid fa-mobile-screen-button"></i> IN-APP PAY
                    </span>
                @else
                    <span class="px-3 py-1 bg-orange-100 text-orange-700 text-[9px] font-black rounded-lg flex items-center gap-1">
                        <i class="fa-solid fa-hand-holding-dollar"></i> HAND TO HAND
                    </span>
                @endif
            </div>
        </div>

        {{-- কাজের তারিখ ও সময় --}}
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500 shrink-0">
                <i class="fa-regular fa-calendar-check"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400">কাজের সময়সূচী</p>
                <p class="text-sm font-bold text-slate-800">
                    {{ \Carbon\Carbon::parse($job->work_date)->format('d M, Y') }} 
                    <span class="text-slate-300 mx-1">|</span>
                    {{ \Carbon\Carbon::parse($job->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($job->end_time)->format('g:i A') }}
                </p>
            </div>
        </div>

        {{-- কর্মী সংখ্যা ও অবস্থান (দূরত্বসহ) --}}
        <div class="grid grid-cols-2 gap-4 pt-2 border-t border-slate-50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500 shrink-0">
                    <i class="fa-solid fa-users text-xs"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 leading-none">কর্মী লাগবে</p>
                    <p class="text-sm font-black text-slate-800">{{ $job->worker_count }} জন</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center text-orange-500 shrink-0">
                    <i class="fa-solid fa-location-arrow text-xs"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 leading-none">দূরত্ব</p>
                    <p id="job-distance" class="text-sm font-black text-slate-800 italic">হিসাব হচ্ছে...</p>
                </div>
            </div>
        </div>
    </div>

    {{-- বর্ণনা --}}
    <div class="mb-8">
        <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">কাজের বিবরণ</h4>
        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
            <p class="text-slate-600 text-sm leading-relaxed font-medium">
                {{ $job->description ?: 'এই কাজের জন্য অতিরিক্ত কোনো বিবরণ দেওয়া হয়নি।' }}
            </p>
        </div>
    </div>

    {{-- ম্যাপ ও রাউটিং --}}
    <div class="mb-8">
        <div class="flex justify-between items-end mb-3 ml-1">
            <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest">লোকেশন ও রুট</h4>
            <p class="text-[11px] font-bold text-indigo-600">{{ $job->location_name }}</p>
        </div>
        <div class="rounded-3xl overflow-hidden h-64 border border-slate-100 shadow-sm relative z-0">
            <div id="mini-map" class="h-full w-full bg-slate-100"></div>
            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $job->lat }},{{ $job->lng }}" target="_blank" 
               class="absolute bottom-3 right-3 z-[1000] bg-white px-4 py-2 rounded-xl shadow-lg text-[11px] font-black text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-map-location-dot text-indigo-500"></i> Open Navigation
            </a>
        </div>
    </div>

    {{-- সতর্কতা --}}
    <div class="mb-10 bg-amber-50 border border-amber-100 p-4 rounded-2xl flex gap-3">
        <i class="fa-solid fa-shield-halved text-amber-500 mt-1"></i>
        <p class="text-[11px] text-amber-800 font-bold leading-relaxed">
            @if($job->payment_type == 'in_app')
                <span class="text-indigo-700">এই কাজের পেমেন্ট অ্যাপের মাধ্যমে হবে।</span> কাজ শেষ করার পর আপনার ওয়ালেটে টাকা যোগ হবে। কোনো অবস্থায় কাজ শুরুর আগে অ্যাপের বাইরে লেনদেন করবেন না।
            @else
                সতর্কতা: এটি ক্যাশ পেমেন্ট কাজ। কাজ শেষ করে সরাসরি নিয়োগকর্তার কাছ থেকে মজুরি বুঝে নিন।
            @endif
        </p>
    </div>

    {{-- অ্যাকশন বাটন --}}
    <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/80 backdrop-blur-lg border-t border-slate-100 z-[4000]">
        @php
            $alreadyApplied = \App\Models\Application::where('job_id', $job->id)
                                ->where('worker_id', auth()->id())
                                ->exists();
            $acceptedCount = $job->applications()->where('status', 'accepted')->count();
            $isFull = $acceptedCount >= $job->worker_count;
        @endphp

        <div class="max-w-lg mx-auto">
            @if($alreadyApplied)
                <button type="button" disabled class="w-full py-4 bg-emerald-100 text-emerald-600 rounded-2xl font-black text-lg flex items-center justify-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> আবেদন সম্পন্ন হয়েছে
                </button>
            @elseif($isFull || $job->status !== 'open')
                <button type="button" disabled class="w-full py-4 bg-slate-100 text-slate-400 rounded-2xl font-black text-lg">
                    @if($isFull) পদ পূর্ণ হয়ে গেছে @else কাজটি বন্ধ আছে @endif
                </button>
            @else
                <form action="{{ route('worker.apply', $job->id) }}" method="POST" id="applyForm">
                    @csrf
                    <button type="submit" 
                            onclick="return confirm('আবেদন করতে আপনার ব্যালেন্স থেকে ১০ টাকা কাটা হবে। আপনি কি নিশ্চিত?')"
                            class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-lg shadow-xl active:scale-95 transition-all flex items-center justify-center gap-2">
                        <span id="btnText">আবেদন করুন</span>
                        <div id="btnLoader" class="hidden w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const destLat = {{ $job->lat }};
        const destLng = {{ $job->lng }};
        
        // ম্যাপ সেটআপ
        var miniMap = L.map('mini-map', {
            zoomControl: true,
            attributionControl: false
        }).setView([destLat, destLng], 14);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(miniMap);

        // কাজের লোকেশন আইকন
        var workIcon = L.divIcon({
            className: 'custom-div-icon',
            html: "<div style='background-color:#4F46E5; width:16px; height:16px; border-radius:50%; border:3px solid white; box-shadow: 0 0 15px rgba(79,70,229,0.4);'></div>",
            iconSize: [16, 16],
            iconAnchor: [8, 8]
        });
        L.marker([destLat, destLng], {icon: workIcon}).addTo(miniMap).bindPopup("কাজের স্থান");

        // ইউজারের বর্তমান লোকেশন বের করা
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;

                // ইউজার আইকন (নীল ডট)
                var userIcon = L.divIcon({
                    className: 'user-icon',
                    html: "<div style='background-color:#0EA5E9; width:12px; height:12px; border-radius:50%; border:2px solid white;'></div>",
                    iconSize: [12, 12],
                    iconAnchor: [6, 6]
                });
                L.marker([userLat, userLng], {icon: userIcon}).addTo(miniMap).bindPopup("আপনি এখানে");

                // রাউটিং (রাস্তা দেখানো)
                var routingControl = L.Routing.control({
                    waypoints: [
                        L.latLng(userLat, userLng),
                        L.latLng(destLat, destLng)
                    ],
                    routeWhileDragging: false,
                    addWaypoints: false,
                    draggableWaypoints: false,
                    createMarker: function() { return null; }, // অতিরিক্ত ডিফল্ট মার্কার বন্ধ
                    lineOptions: {
                        styles: [{ color: '#6366F1', weight: 5, opacity: 0.7 }]
                    }
                }).addTo(miniMap);

                // দূরত্ব হিসাব ও টেক্সট আপডেট
                routingControl.on('routesfound', function(e) {
                    var routes = e.routes;
                    var summary = routes[0].summary;
                    var distance = (summary.totalDistance / 1000).toFixed(1); // মি.মি. থেকে কি.মি.
                    document.getElementById('job-distance').innerText = distance + " কি.মি. দূরে";
                    
                    // ম্যাপকে অটো ফিট করা যাতে দুটি পয়েন্টই দেখা যায়
                    var bounds = L.latLngBounds([userLat, userLng], [destLat, destLng]);
                    miniMap.fitBounds(bounds, {padding: [50, 50]});
                });

                // নিচের ইনস্ট্রাকশন বক্স হাইড করার জন্য CSS
                document.querySelector('.leaflet-routing-container').style.display = 'none';

            }, function() {
                document.getElementById('job-distance').innerText = "লোকেশন অনুমতি নেই";
            });
        } else {
            document.getElementById('job-distance').innerText = "সাপোর্ট করে না";
        }

        // ফর্ম সাবমিশন হ্যান্ডলার
        const applyForm = document.getElementById('applyForm');
        if(applyForm) {
            applyForm.addEventListener('submit', function() {
                document.getElementById('btnText').innerText = 'প্রসেসিং...';
                document.getElementById('btnLoader').classList.remove('hidden');
            });
        }
    });
</script>

<style>
    body { background-color: #fff; }
    .font-sans { font-family: 'Inter', 'Hind Siliguri', sans-serif; }
    /* Routing প্যানেল লুকানোর জন্য */
    .leaflet-routing-container { display: none !important; }
</style>
@endsection