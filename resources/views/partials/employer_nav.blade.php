{{-- resources/views/partials/employer_nav.blade.php --}}

<nav class="fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-md border-t border-slate-100 px-6 py-4 flex justify-between items-center z-[5000] shadow-[0_-5px_15px_rgba(0,0,0,0.02)]">
    
    {{-- হোম --}}
    <a href="{{ route('employer.home') }}" 
       class="flex flex-col items-center gap-1 transition-all duration-300 active:scale-90 {{ request()->routeIs('employer.home') ? 'text-emerald-600' : 'text-slate-400' }}">
        <i class="fa-solid fa-house-chimney text-lg"></i>
        <span class="text-[10px] font-bold">হোম</span>
    </a>

    {{-- কাজ (Job List) --}}
    <a href="{{ route('employer.job.list') }}" 
       class="flex flex-col items-center gap-1 transition-all duration-300 active:scale-90 {{ request()->routeIs('employer.job.list') ? 'text-emerald-600' : 'text-slate-400' }}">
        <i class="fa-solid fa-briefcase text-lg"></i>
        <span class="text-[10px] font-bold">কাজ</span>
    </a>

    {{-- এলার্ট / নোটিফিকেশন --}}
    <a href="{{ route('employer.notifications.index') }}" 
       class="relative flex flex-col items-center gap-1 transition-all duration-300 active:scale-90 {{ request()->routeIs('employer.notifications.index') ? 'text-emerald-600' : 'text-slate-400' }}">
        <div class="relative">
            <i class="fa-solid fa-bell text-lg"></i>
            @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
            @if($unreadCount > 0)
                <span class="absolute -top-1 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[8px] font-bold text-white border-2 border-white">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
            @endif
        </div>
        <span class="text-[10px] font-bold">এলার্ট</span>
    </a>

    {{-- আবেদন --}}
    <a href="{{ route('employer.applicants') }}" 
       class="flex flex-col items-center gap-1 transition-all duration-300 active:scale-90 {{ request()->routeIs('employer.applicants') ? 'text-emerald-600' : 'text-slate-400' }}">
        <i class="fa-solid fa-file-lines text-lg"></i>
        <span class="text-[10px] font-bold">আবেদন</span>
    </a>

    {{-- প্রোফাইল --}}
    <a href="{{ route('employer.profile') }}" 
       class="flex flex-col items-center gap-1 transition-all duration-300 active:scale-90 {{ request()->routeIs('employer.profile') ? 'text-emerald-600' : 'text-slate-400' }}">
        <i class="fa-solid fa-circle-user text-lg"></i>
        <span class="text-[10px] font-bold">প্রোফাইল</span>
    </a>
</nav>