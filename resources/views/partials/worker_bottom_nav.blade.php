<div class="fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-lg border-t border-slate-100 px-6 py-4 flex justify-between items-center z-[2000] shadow-[0_-10px_25px_rgba(0,0,0,0.05)] rounded-t-[2rem]">
    
    <a href="{{ route('worker.home') }}" class="flex flex-col items-center gap-1.5 transition-all duration-300 {{ request()->routeIs('worker.home') ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
        <div class="relative">
            <i class="fa-solid fa-house-chimney text-xl"></i>
            @if(request()->routeIs('worker.home'))
                <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-indigo-600 rounded-full"></span>
            @endif
        </div>
        <span class="text-[10px] font-black uppercase tracking-tighter">হোম</span>
    </a>

    <a href="{{ route('worker.notifications') }}" 
       class="relative flex flex-col items-center gap-1.5 transition-all duration-300 {{ request()->routeIs('worker.notifications') ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
        <div class="relative">
            <i class="fa-solid fa-bell text-xl"></i>
            
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="absolute -top-1.5 -right-1.5 flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-[10px] text-white flex items-center justify-center font-bold">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                </span>
            @endif

            @if(request()->routeIs('worker.notifications'))
                <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-indigo-600 rounded-full"></span>
            @endif
        </div>
        <span class="text-[10px] font-black uppercase tracking-tighter">খবর</span>
    </a>

    <a href="{{ route('worker.applied') }}" class="flex flex-col items-center gap-1.5 transition-all duration-300 {{ request()->routeIs('worker.applied') ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
        <div class="relative">
            <i class="fa-solid fa-file-lines text-xl"></i>
            @if(request()->routeIs('worker.applied'))
                <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-indigo-600 rounded-full"></span>
            @endif
        </div>
        <span class="text-[10px] font-black uppercase tracking-tighter">আবেদন</span>
    </a>

    <a href="{{ route('worker.profile.index') }}" class="flex flex-col items-center gap-1.5 transition-all duration-300 {{ request()->routeIs('worker.profile*') ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
        <div class="relative">
            <i class="fa-solid fa-circle-user text-xl"></i>
            @if(request()->routeIs('worker.profile*'))
                <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-indigo-600 rounded-full"></span>
            @endif
        </div>
        <span class="text-[10px] font-black uppercase tracking-tighter">প্রোফাইল</span>
    </a>
</div>

<style>
    /* আইকন অ্যানিমেশন */
    .text-indigo-600 i {
        transform: translateY(-2px);
        transition: transform 0.3s ease;
    }
</style>