@extends('layouts.employer')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="px-6 pt-8 pb-24 max-w-lg mx-auto bg-white min-h-screen">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">নতুন কাজ</h2>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">সঠিক তথ্য দিয়ে কর্মী খুঁজে নিন</p>
        </div>
        <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 shadow-sm">
            <i class="fa-solid fa-briefcase text-xl"></i>
        </div>
    </div>
    
    <form id="jobPostForm" action="{{ route('employer.job.store') }}" method="POST" class="space-y-6">
        @csrf
        
        {{-- কাজের নাম --}}
        <div class="group">
            <label class="block text-[10px] font-black text-slate-400 mb-1.5 uppercase tracking-widest ml-1 transition-colors group-focus-within:text-indigo-600">কাজের নাম:</label>
            <div class="relative">
                <input type="text" name="title" value="{{ old('title') }}" placeholder="উদা: অভিজ্ঞ রাজমিস্ত্রি চাই" class="w-full p-4 rounded-2xl border-2 border-slate-100 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all font-bold text-slate-700 bg-slate-50/50 focus:bg-white" required>
            </div>
        </div>

        {{-- ক্যাটাগরি এবং কর্মী সংখ্যা --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-[10px] font-black text-slate-400 mb-1.5 uppercase tracking-widest ml-1">ক্যাটাগরি:</label>
                <div class="relative">
                    <select name="category" class="w-full p-4 rounded-2xl border-2 border-slate-100 focus:border-indigo-500 outline-none appearance-none bg-slate-50/50 font-bold text-slate-700 text-sm transition-all cursor-pointer" required>
                        <option value="">সিলেক্ট করুন</option>
                        <optgroup label="🏗️ Construction">
                            <option value="রাজমিস্ত্রি" {{ old('category') == 'রাজমিস্ত্রি' ? 'selected' : '' }}>রাজমিস্ত্রি</option>
                            <option value="হেল্পার/লেবার" {{ old('category') == 'হেল্পার/লেবার' ? 'selected' : '' }}>হেল্পার / লেবার</option>
                            <option value="কাঠ মিস্ত্রি" {{ old('category') == 'কাঠ মিস্ত্রি' ? 'selected' : '' }}>কাঠ মিস্ত্রি</option>
                            <option value="রং মিস্ত্রি" {{ old('category') == 'রং মিস্ত্রি' ? 'selected' : '' }}>রং মিস্ত্রি</option>
                        </optgroup>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                        <i class="fa-solid fa-angle-down text-xs"></i>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 mb-1.5 uppercase tracking-widest ml-1">কর্মী সংখ্যা:</label>
                <div class="relative">
                    <input type="number" id="worker_count" name="worker_count" value="{{ old('worker_count', 1) }}" min="1" max="50" oninput="calculateTotal()" class="w-full p-4 rounded-2xl border-2 border-slate-100 focus:border-indigo-500 outline-none font-bold text-slate-700 bg-slate-50/50 transition-all" required>
                    <div class="absolute right-4 top-4 text-indigo-300">
                        <i class="fa-solid fa-user-group text-sm"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- পেমেন্ট মেথড --}}
        <div class="space-y-3">
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">পেমেন্ট করার পদ্ধতি:</label>
            <div class="grid grid-cols-2 gap-3">
                <label class="relative flex flex-col p-4 bg-white rounded-[1.5rem] border-2 border-slate-100 cursor-pointer hover:border-indigo-200 transition-all group has-[:checked]:border-indigo-600 has-[:checked]:ring-4 has-[:checked]:ring-indigo-50 shadow-sm">
                    <input type="radio" name="payment_type" value="cash" onclick="toggleWalletDisplay(false)" class="absolute opacity-0" {{ old('payment_type', 'cash') == 'cash' ? 'checked' : '' }}>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center group-has-[:checked]:border-indigo-600 group-has-[:checked]:bg-indigo-600 transition-all">
                            <div class="w-2 h-2 rounded-full bg-white scale-0 group-has-[:checked]:scale-100 transition-transform"></div>
                        </div>
                        <span class="text-xs font-black text-slate-700">হাতে হাতে</span>
                    </div>
                    <p class="text-[9px] font-bold text-slate-400">সরাসরি ক্যাশ পেমেন্ট</p>
                </label>

                <label class="relative flex flex-col p-4 bg-white rounded-[1.5rem] border-2 border-slate-100 cursor-pointer hover:border-indigo-200 transition-all group has-[:checked]:border-indigo-600 has-[:checked]:ring-4 has-[:checked]:ring-indigo-50 shadow-sm">
                    <input type="radio" name="payment_type" value="in_app" onclick="toggleWalletDisplay(true)" class="absolute opacity-0" {{ old('payment_type') == 'in_app' ? 'checked' : '' }}>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center group-has-[:checked]:border-indigo-600 group-has-[:checked]:bg-indigo-600 transition-all">
                            <div class="w-2 h-2 rounded-full bg-white scale-0 group-has-[:checked]:scale-100 transition-transform"></div>
                        </div>
                        <span class="text-xs font-black text-slate-700">অ্যাপ ওয়ালেট</span>
                    </div>
                    <p class="text-[9px] font-bold text-slate-400">ব্যালেন্স থেকে পেমেন্ট</p>
                </label>
            </div>

            <div id="wallet_balance_card" class="hidden transform scale-95 opacity-0 transition-all duration-300">
                <div class="bg-indigo-50 border-2 border-indigo-100 rounded-2xl p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                            <i class="fa-solid fa-wallet text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-indigo-400 uppercase leading-none mb-1">আপনার বর্তমান ব্যালেন্স</p>
                            <p class="text-lg font-black text-slate-800 leading-none">৳ {{ number_format(auth()->user()->wallet ?? 0, 2) }}</p>
                        </div>
                    </div>
                    <a href="{{ route('employer.wallet') }}" class="text-[10px] font-black bg-white text-indigo-600 px-3 py-2 rounded-lg shadow-sm border border-indigo-50 hover:bg-indigo-600 hover:text-white transition-all">টাকা ভরুন +</a>
                </div>
            </div>
        </div>

        {{-- টাইম সেকশন --}}
        <div class="bg-indigo-600 p-6 rounded-[2.5rem] shadow-xl shadow-indigo-100 space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-6 h-6 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-clock text-white text-[10px]"></i>
                </div>
                <span class="text-[10px] font-black text-indigo-100 uppercase tracking-[0.2em]">সময় ও তারিখ</span>
            </div>
            <div>
                <input type="date" name="work_date" value="{{ old('work_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" class="w-full p-4 rounded-2xl bg-white/10 border border-white/20 outline-none font-bold text-white placeholder-indigo-200" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <input type="time" name="start_time" value="{{ old('start_time', '08:00') }}" class="w-full p-4 rounded-2xl bg-white/10 border border-white/20 outline-none font-bold text-white" required>
                <input type="time" name="end_time" value="{{ old('end_time', '17:00') }}" class="w-full p-4 rounded-2xl bg-white/10 border border-white/20 outline-none font-bold text-white" required>
            </div>
        </div>

        {{-- লোকেশন ও ম্যাপ --}}
        <div class="space-y-3 relative">
            <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-widest ml-1">কাজের লোকেশন:</label>
            <div class="relative group">
                <input type="text" id="location_input" name="location_name" value="{{ old('location_name') }}" placeholder="এলাকার নাম লিখে খুঁজুন" autocomplete="off"
                    class="w-full p-4 pr-14 rounded-2xl border-2 border-slate-100 focus:border-indigo-500 outline-none font-bold text-slate-700 transition-all bg-slate-50/50" required>
                <div id="search_results" class="absolute left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 z-[9999] hidden max-h-60 overflow-y-auto"></div>
                <button type="button" onclick="getCurrentLocation()" class="absolute right-2 top-2 w-11 h-11 bg-white shadow-sm border border-slate-100 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all flex items-center justify-center active:scale-90">
                    <i class="fa-solid fa-location-arrow text-lg"></i>
                </button>
            </div>
            <div id="map" class="h-52 w-full rounded-[2.5rem] border-4 border-slate-50 z-0 shadow-sm overflow-hidden"></div>
            <input type="hidden" name="lat" id="lat" value="{{ old('lat') }}" required>
            <input type="hidden" name="lng" id="lng" value="{{ old('lng') }}" required>
        </div>

        {{-- বাজেট কার্ড --}}
        <div class="bg-slate-900 p-6 rounded-[2.5rem] text-white space-y-5">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[9px] font-black text-slate-400 mb-2 uppercase tracking-widest">মজুরির ধরণ:</label>
                    <select id="wage_type" name="wage_type" onchange="updateWageUI()" class="w-full p-3.5 rounded-xl bg-white/10 border border-white/10 outline-none font-bold text-sm transition-all focus:bg-white/20">
                        <option value="daily" class="text-slate-800">দিন ভিত্তিক</option>
                        <option value="hourly" class="text-slate-800">ঘণ্টা ভিত্তিক</option>
                    </select>
                </div>
                <div>
                    <label id="wage_label" class="block text-[9px] font-black text-slate-400 mb-2 uppercase tracking-widest">প্রতিদিনের মজুরি:</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3.5 text-indigo-400 font-black">৳</span>
                        <input type="number" id="wage_input" name="wage" value="{{ old('wage') }}" placeholder="৫০০" oninput="calculateTotal()" class="w-full p-3.5 pl-7 rounded-xl bg-white/10 border border-white/10 outline-none font-black text-white focus:bg-white/20 transition-all" required>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-white/10">
                <div class="flex-1">
                    <label id="duration_label" class="block text-[9px] font-black text-slate-400 mb-2 uppercase tracking-widest">কাজের মেয়াদ (দিন):</label>
                    <input type="number" id="duration_input" name="duration" value="{{ old('duration', 1) }}" min="1" oninput="calculateTotal()" class="w-full p-3.5 rounded-xl bg-white/10 border border-white/10 outline-none font-black text-white focus:bg-white/20" required>
                </div>
                <div class="flex-1 bg-indigo-600 p-4 rounded-2xl text-center shadow-lg shadow-indigo-900/20">
                    <span class="text-[8px] font-black text-indigo-200 uppercase block mb-1">মোট বাজেট</span>
                    <span id="total_budget_text" class="text-2xl font-black text-white">৳ ০</span>
                </div>
            </div>
        </div>

        {{-- বর্ণনা --}}
        <div class="group">
            <label class="block text-[10px] font-black text-slate-400 mb-1.5 uppercase tracking-widest ml-1 group-focus-within:text-indigo-600">বিস্তারিত বর্ণনা:</label>
            <textarea name="description" rows="3" class="w-full p-4 rounded-2xl border-2 border-slate-100 focus:border-indigo-500 outline-none font-bold text-slate-700 bg-slate-50/50 transition-all" placeholder="কাজের নির্দিষ্ট কোনো শর্ত থাকলে এখানে লিখুন...">{{ old('description') }}</textarea>
        </div>

        <button type="submit" id="submitBtn" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-5 rounded-[2rem] font-black text-xl shadow-xl shadow-indigo-200 active:scale-[0.98] transition-all flex items-center justify-center gap-3">
            <span>পাবলিশ করুন</span>
            <i class="fa-solid fa-arrow-right-long text-indigo-300"></i>
        </button>
    </form>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // --- Map Logic ---
    var map = L.map('map').setView([23.8103, 90.4125], 13);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(map);
    var marker;

    function updateMarker(lat, lng, name = null) {
        if (marker) { map.removeLayer(marker); }
        marker = L.marker([lat, lng]).addTo(map);
        map.setView([lat, lng], 15);
        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;
        
        if (name) { 
            document.getElementById('location_input').value = name; 
            hideResults();
        } else {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(res => res.json())
                .then(data => {
                    if(data.display_name) {
                        let addr = data.display_name.split(',');
                        let shortAddr = addr[0] + (addr[1] ? ',' + addr[1] : '');
                        document.getElementById('location_input').value = shortAddr;
                    }
                }).catch(err => console.error("Map reverse geocode error:", err));
        }
    }

    // --- Search Logic ---
    const locationInput = document.getElementById('location_input');
    const resultsBox = document.getElementById('search_results');
    let debounceTimer;

    locationInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const query = this.value;
        if (query.length < 3) { hideResults(); return; }
        debounceTimer = setTimeout(() => {
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}&countrycodes=bd&limit=5`)
                .then(res => res.json())
                .then(data => {
                    resultsBox.innerHTML = '';
                    if (data.length > 0) {
                        resultsBox.classList.remove('hidden');
                        data.forEach(place => {
                            const item = document.createElement('div');
                            item.className = "p-4 border-b border-slate-50 hover:bg-indigo-50 cursor-pointer transition-all";
                            item.innerHTML = `<div class="flex items-start gap-2"><i class="fa-solid fa-location-dot mt-1 text-indigo-500"></i><p class="text-[12px] font-bold text-slate-700">${place.display_name}</p></div>`;
                            item.onclick = () => updateMarker(place.lat, place.lon, place.display_name);
                            resultsBox.appendChild(item);
                        });
                    } else { hideResults(); }
                });
        }, 400);
    });

    function hideResults() { resultsBox.classList.add('hidden'); }
    map.on('click', (e) => updateMarker(e.latlng.lat, e.latlng.lng));

    function getCurrentLocation() {
        if (navigator.geolocation) {
            const btn = event.currentTarget;
            btn.classList.add('animate-pulse');
            navigator.geolocation.getCurrentPosition(position => {
                updateMarker(position.coords.latitude, position.coords.longitude);
                btn.classList.remove('animate-pulse');
            }, () => {
                btn.classList.remove('animate-pulse');
                alert("লোকেশন পাওয়া যায়নি। অনুগ্রহ করে পারমিশন চেক করুন।");
            });
        }
    }

    // --- UI Logic ---
    function toggleWalletDisplay(show) {
        const card = document.getElementById('wallet_balance_card');
        if(show) {
            card.classList.remove('hidden');
            setTimeout(() => card.classList.remove('scale-95', 'opacity-0'), 10);
        } else {
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => card.classList.add('hidden'), 300);
        }
    }

    function updateWageUI() {
        const type = document.getElementById('wage_type').value;
        document.getElementById('wage_label').innerText = type === 'hourly' ? "প্রতি ঘণ্টার মজুরি:" : "প্রতিদিনের মজুরি:";
        document.getElementById('duration_label').innerText = type === 'hourly' ? "কাজের মেয়াদ (ঘণ্টা):" : "কাজের মেয়াদ (দিন):";
        calculateTotal();
    }

    function calculateTotal() {
        const wage = parseFloat(document.getElementById('wage_input').value) || 0;
        const duration = parseFloat(document.getElementById('duration_input').value) || 0;
        const workerCount = parseFloat(document.getElementById('worker_count').value) || 0;
        const total = wage * duration * workerCount;
        document.getElementById('total_budget_text').innerText = "৳ " + total.toLocaleString();
    }

    // --- Fixed AJAX Submission ---
    document.getElementById('jobPostForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const btn = document.getElementById('submitBtn');
        const originalContent = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i> <span>প্রসেসিং হচ্ছে...</span>`;

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json' // Tell Laravel to return JSON
            }
        })
        .then(async response => {
            const contentType = response.headers.get("content-type");
            
            // If response is JSON
            if (contentType && contentType.indexOf("application/json") !== -1) {
                const data = await response.json();
                if (response.ok) {
                    Swal.fire({
                        title: '<span class="font-black text-slate-800">অসাধারণ!</span>',
                        html: '<p class="font-bold text-slate-500">আপনার কাজটি সফলভাবে পোস্ট হয়েছে।</p>',
                        icon: 'success',
                        confirmButtonText: 'ড্যাশবোর্ডে যান',
                        confirmButtonColor: '#4f46e5',
                        customClass: { popup: 'rounded-[2.5rem]', confirmButton: 'px-8 py-4 rounded-2xl font-black text-lg' }
                    }).then(() => { window.location.href = "{{ route('employer.home') }}"; });
                } else {
                    throw new Error(data.message || 'ভুল তথ্য দেওয়া হয়েছে');
                }
            } else {
                // If response is HTML (Error 500 or redirect)
                const errorText = await response.text();
                console.error("Server Error Response:", errorText);
                throw new Error('সার্ভারে সমস্যা হয়েছে। ব্রাউজার কনসোল চেক করুন।');
            }
        })
        .catch(error => {
            btn.disabled = false;
            btn.innerHTML = originalContent;
            Swal.fire({
                title: 'দুঃখিত!',
                text: error.message,
                icon: 'error',
                confirmButtonColor: '#ef4444',
                customClass: { popup: 'rounded-[2rem]' }
            });
        });
    });

    window.onload = () => {
        updateWageUI();
        const checkedPayment = document.querySelector('input[name="payment_type"]:checked');
        if(checkedPayment && checkedPayment.value === 'in_app') {
            toggleWalletDisplay(true);
        }
    };
</script>
@endsection