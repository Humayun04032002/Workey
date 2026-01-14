<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workey - Worker Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap');
        
        body {
            font-family: 'Hind Siliguri', sans-serif;
            margin: 0;
            overflow-x: hidden;
            -webkit-tap-highlight-color: transparent;
        }
        [x-cloak] { display: none !important; }
        
        .app-container {
            min-height: 100vh;
            padding-bottom: 90px; 
        }
        .notification-scroll::-webkit-scrollbar { width: 4px; }
        .notification-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</head>

<body class="bg-slate-50" x-data="{ openNotifications: false }" x-cloak>

    <div class="app-container">
        <div x-show="openNotifications" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-20"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-20"
             @click.away="openNotifications = false"
             class="fixed bottom-24 left-4 right-4 bg-white rounded-3xl shadow-[0_-15px_50px_rgba(0,0,0,0.15)] z-[9999] border border-slate-100 overflow-hidden flex flex-col max-h-[75vh]">
            
            <div class="p-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-bell text-indigo-600 animate-pulse"></i>
                    <h3 class="font-bold text-slate-800">নোটিফিকেশন</h3>
                </div>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <a href="{{ route('markRead') }}" class="text-[10px] font-bold text-white bg-indigo-600 px-3 py-1.5 rounded-full uppercase tracking-wider shadow-sm">সব পড়ুন</a>
                @endif
            </div>

            <div class="overflow-y-auto notification-scroll bg-white">
                @forelse(auth()->user()->notifications()->take(15)->get() as $notification)
                    <div class="p-4 border-b border-slate-50 flex gap-4 transition-all hover:bg-slate-50 {{ $notification->read_at ? 'opacity-50' : 'bg-indigo-50/30' }}">
                        <div class="flex-shrink-0 mt-1">
                            @php
                                $type = $notification->data['type'] ?? 'info';
                                $icon = match($type) {
                                    'success' => ['fa-circle-check', 'text-emerald-500'],
                                    'danger'  => ['fa-circle-xmark', 'text-rose-500'],
                                    'wallet'  => ['fa-wallet', 'text-amber-500'],
                                    default   => ['fa-circle-info', 'text-blue-500'],
                                };
                            @endphp
                            <i class="fa-solid {{ $icon[0] }} {{ $icon[1] }} text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-800 leading-tight">{{ $notification->data['title'] }}</p>
                            <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">{{ $notification->data['message'] }}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <i class="fa-regular fa-clock text-[9px] text-slate-400"></i>
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wide">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-16 text-center">
                        <div class="bg-slate-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 border border-dashed border-slate-200">
                            <i class="fa-solid fa-bell-slash text-slate-200 text-2xl"></i>
                        </div>
                        <p class="text-slate-400 text-sm font-medium italic">কোন নোটিফিকেশন নেই</p>
                    </div>
                @endforelse
            </div>
            
            <div @click="openNotifications = false" class="p-4 text-center bg-white border-t border-slate-50 cursor-pointer hover:bg-slate-50">
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em]">বন্ধ করুন</p>
            </div>
        </div>

        @yield('content')
    </div>

    @include('partials.worker_bottom_nav') 

    <script>
        document.addEventListener('alpine:init', () => {
            console.log('Alpine initialized successfully');
        });
    </script>
</body>
</html>