@extends('layouts.worker')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="relative h-screen w-full overflow-hidden bg-slate-50" x-data="jobApp()">
    
    {{-- Top Search & Balance Bar --}}
    <div class="fixed top-6 left-0 right-0 z-[3000] px-4 pointer-events-none">
        <div class="max-w-xl mx-auto flex items-center bg-white shadow-2xl rounded-2xl p-1.5 border border-slate-100 pointer-events-auto gap-1">
            
            <div class="flex items-center gap-2 bg-slate-50 px-3 py-2 rounded-xl shrink-0 border border-slate-100">
                <div class="w-4 h-4 bg-indigo-100 rounded-full flex items-center justify-center text-[8px] text-indigo-600">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <span class="text-slate-800 text-[11px] font-black tracking-tighter">
                    ৳{{ number_format(auth()->user()->balance ?? 0, 0) }}
                </span>
            </div>

            <div class="flex flex-1 items-center px-2">
                <i class="fa-solid fa-magnifying-glass text-slate-300 text-xs mr-2"></i>
                <input type="text" x-model="searchQuery" @input="filterData()" placeholder="কাজ খুঁজুন..." 
                       class="w-full bg-transparent outline-none text-xs font-bold text-slate-700 placeholder:text-slate-400">
            </div>
            
            <div class="flex bg-slate-100 p-1 rounded-xl shrink-0">
                <button @click="viewMode = 'map'" :class="viewMode === 'map' ? 'bg-white shadow-sm text-indigo-600' : 'text-slate-400'" 
                        class="px-3 py-1.5 rounded-lg transition-all">
                    <i class="fa-solid fa-map text-[10px]"></i>
                </button>
                <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-white shadow-sm text-indigo-600' : 'text-slate-400'" 
                        class="px-3 py-1.5 rounded-lg transition-all">
                    <i class="fa-solid fa-list-ul text-[10px]"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Map View --}}
    <div x-show="viewMode === 'map'" class="h-full w-full relative">
        <div id="map" class="h-full w-full z-0 bg-slate-100"></div>
        <div class="absolute bottom-40 right-6 z-[4000]">
            <button @click="locateUser()" class="bg-white w-14 h-14 rounded-full shadow-2xl flex items-center justify-center border border-slate-100 active:scale-90 transition-all text-indigo-600">
                <i class="fa-solid fa-location-crosshairs text-xl"></i>
            </button>
        </div>
    </div>

    {{-- List View --}}
    <div x-show="viewMode === 'list'" x-cloak class="h-full w-full pt-28 pb-10 px-4 overflow-y-auto bg-slate-50">
        <div class="max-w-xl mx-auto space-y-3">
            <template x-for="job in filteredJobs" :key="job.id">
                <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col gap-3">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-slate-800 text-sm" x-text="job.title"></h4>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-slate-400 font-bold uppercase" x-text="job.category"></span>
                                <span class="text-[10px] text-indigo-500 font-black">
                                    • <i class="fa-solid fa-location-dot mr-1"></i><span x-text="getDistance(job.lat, job.lng)"></span>
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-indigo-600 font-black text-sm">
                                ৳<span x-text="Math.round(job.wage)"></span><span class="text-[9px] text-slate-400 font-medium" x-text="job.wage_type === 'daily' ? '/দিন' : '/ঘণ্টা'"></span>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Date & Time Display --}}
                    <div class="flex items-center justify-between bg-slate-50 p-2 rounded-2xl border border-slate-100">
                        <div class="flex items-center gap-2 px-2 text-slate-600">
                            <i class="fa-regular fa-calendar-check text-indigo-500 text-xs"></i>
                            <span class="text-[11px] font-bold" x-text="formatJobDateTime(job.work_date, job.start_time)"></span>
                        </div>
                        <a :href="'/worker/job/' + job.id" class="text-[9px] font-black bg-slate-900 text-white px-4 py-2 rounded-xl uppercase">বিস্তারিত</a>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    var map, userMarker, markers = [];
    var allJobs = @json($nearbyJobs);

    function jobApp() {
        return {
            viewMode: 'map',
            searchQuery: '',
            filteredJobs: allJobs,
            userLocation: null,

            init() {
                this.$nextTick(() => {
                    this.initMap();
                    this.renderMarkers();
                    setTimeout(() => this.locateUser(), 1000);
                });
            },

            formatJobDateTime(dateStr, timeStr) {
                const date = new Date(dateStr + ' ' + timeStr);
                
                // Bangla Am/Pm logic
                let hours = date.getHours();
                let ampm = hours >= 12 ? 'বিকাল' : 'সকাল';
                if(hours >= 18) ampm = 'রাত';
                if(hours >= 12 && hours < 16) ampm = 'দুপুর';
                hours = hours % 12;
                hours = hours ? hours : 12;

                const day = date.getDate();
                const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                const month = monthNames[date.getMonth()];
                
                const dayNames = ["রবিবার", "সোমবার", "মঙ্গলবার", "বুধবার", "বৃহস্পতিবার", "শুক্রবার", "শনিবার"];
                const dayName = dayNames[date.getDay()];

                return `${ampm} ${hours}টা, ${day} ${month}, ${dayName}`;
            },

            getDistance(lat2, lon2) {
                if (!this.userLocation) return '...';
                const lat1 = this.userLocation.lat, lon1 = this.userLocation.lng;
                const R = 6371;
                const dLat = (lat2 - lat1) * Math.PI / 180, dLon = (lon2 - lon1) * Math.PI / 180;
                const a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon/2) * Math.sin(dLon/2);
                const d = R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)));
                return d < 1 ? (d * 1000).toFixed(0) + ' মিটার' : d.toFixed(1) + ' কিমি';
            },

            initMap() {
                map = L.map('map', { zoomControl: false, attributionControl: false }).setView([23.8103, 90.4125], 13);
                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(map);
                var userIcon = L.divIcon({
                    className: 'user-marker',
                    html: `<div class='relative flex items-center justify-center'><div class='w-4 h-4 bg-blue-600 rounded-full border-2 border-white shadow-lg z-10'></div><div class='absolute w-8 h-8 bg-blue-400 rounded-full opacity-30 animate-ping'></div></div>`,
                    iconSize: [20, 20], iconAnchor: [10, 10]
                });
                userMarker = L.marker([23.8103, 90.4125], {icon: userIcon, zIndexOffset: 1000}).addTo(map);
            },

            filterData() {
                const q = this.searchQuery.toLowerCase();
                this.filteredJobs = allJobs.filter(j => j.title.toLowerCase().includes(q) || j.category.toLowerCase().includes(q));
                this.renderMarkers();
            },

            renderMarkers() {
                markers.forEach(m => map.removeLayer(m));
                markers = [];
                this.filteredJobs.forEach(job => {
                    if(job.lat && job.lng) {
                        const icon = L.divIcon({
                            className: 'job-icon',
                            html: `<div class="marker-pin"><i class="fa-solid fa-briefcase"></i></div>`,
                            iconSize: [40, 40], iconAnchor: [20, 40]
                        });
                        const m = L.marker([job.lat, job.lng], {icon: icon}).addTo(map);
                        
                        const popup = `
                            <div class="map-card">
                                <h3 class="map-title">${job.title}</h3>
                                <div class="map-price">৳${Math.round(job.wage)}<span class="text-xs text-slate-400">${job.wage_type === 'daily' ? '/দিন' : '/ঘণ্টা'}</span></div>
                                <div class="map-time"><i class="fa-solid fa-calendar-day mr-1"></i> ${this.formatJobDateTime(job.work_date, job.start_time)}</div>
                                <a href="/worker/job/${job.id}" class="map-btn">বিস্তারিত</a>
                            </div>`;
                        m.bindPopup(popup, { closeButton: false, className: 'custom-map-popup' });
                        markers.push(m);
                    }
                });
            },

            locateUser() {
                if (!navigator.geolocation) return;
                navigator.geolocation.getCurrentPosition((p) => {
                    this.userLocation = { lat: p.coords.latitude, lng: p.coords.longitude };
                    userMarker.setLatLng([this.userLocation.lat, this.userLocation.lng]);
                    map.flyTo([this.userLocation.lat, this.userLocation.lng], 15);
                    this.renderMarkers();
                }, null, { enableHighAccuracy: true });
            }
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
    body { font-family: 'Inter', sans-serif; overflow: hidden; }
    .custom-map-popup .leaflet-popup-content-wrapper { border-radius: 20px; padding: 0; }
    .custom-map-popup .leaflet-popup-content { margin: 0 !important; width: 220px !important; }
    .map-card { padding: 15px; text-align: center; }
    .map-title { font-size: 14px; font-weight: 800; color: #1e293b; margin-bottom: 5px; }
    .map-price { font-size: 18px; font-weight: 900; color: #4f46e5; }
    .map-time { font-size: 9px; color: #64748b; margin-top: 5px; margin-bottom: 10px; font-weight: 700; }
    .map-btn { display: block; background: #0f172a; color: white; padding: 8px; border-radius: 10px; font-size: 11px; text-decoration: none; font-weight: 700; }
    .marker-pin { width: 34px; height: 34px; background: #4f46e5; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); display: flex; align-items: center; justify-content: center; border: 2px white solid; }
    .marker-pin i { transform: rotate(45deg); color: white; font-size: 12px; }
    @keyframes ping { 75%, 100% { transform: scale(2.2); opacity: 0; } }
    .animate-ping { animation: ping 2s infinite; }
    #map { height: 100vh; width: 100%; position: absolute; top: 0; left: 0; }
</style>
@endsection