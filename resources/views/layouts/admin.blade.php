<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workey Admin - Control Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex">
    <aside class="w-64 bg-slate-900 min-h-screen text-white p-6 sticky top-0 h-screen flex flex-col">
        <h2 class="text-2xl font-black mb-10 text-indigo-400 tracking-tighter">Workey <span class="text-white">Admin</span></h2>
        
        <nav class="space-y-2 flex-1">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center p-3 rounded-xl transition font-bold {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-400' }}">
                <i class="fa-solid fa-chart-line w-6"></i> Dashboard
            </a>

            <a href="{{ route('admin.users') }}?role=worker" 
               class="flex items-center p-3 rounded-xl transition font-bold {{ request('role') == 'worker' ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-400' }}">
                <i class="fa-solid fa-users w-6"></i> Worker Management
            </a>

            <a href="{{ route('admin.users') }}?role=employer" 
               class="flex items-center p-3 rounded-xl transition font-bold {{ request('role') == 'employer' ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-400' }}">
                <i class="fa-solid fa-building w-6"></i> Employer Management
            </a>

            <a href="{{ route('admin.jobs') }}" 
               class="flex items-center p-3 rounded-xl transition font-bold {{ request()->routeIs('admin.jobs') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-400' }}">
                <i class="fa-solid fa-briefcase w-6"></i> Job Management
            </a>

            <a href="{{ route('admin.payments') }}" 
               class="flex items-center p-3 rounded-xl transition font-bold {{ request()->routeIs('admin.payments') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-400' }}">
                <i class="fa-solid fa-wallet w-6"></i> Payments
            </a>

            <a href="{{ route('admin.verifications') }}" 
               class="flex items-center p-3 rounded-xl transition font-bold {{ request()->routeIs('admin.verifications') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-400' }}">
                <i class="fa-solid fa-shield-check w-6"></i> Verifications
            </a>

            <a href="{{ route('admin.settings') }}" 
               class="flex items-center p-3 rounded-xl transition font-bold {{ request()->routeIs('admin.settings') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-400' }}">
                <i class="fa-solid fa-gear w-6"></i> System Settings
            </a>
        </nav>

        <div class="mt-auto pt-6 border-t border-slate-800">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left p-3 rounded-xl text-rose-400 hover:bg-rose-500/10 transition font-bold flex items-center shadow-none border-none cursor-pointer">
                    <i class="fa-solid fa-right-from-bracket w-6"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 p-8 overflow-y-auto h-screen bg-slate-50">
        {{-- সাকসেস বা এরর মেসেজ প্রদর্শনের জন্য --}}
        @if(session('success'))
            <div class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-100 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl mb-6 font-bold">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>