<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Workey</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-slate-100 w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-slate-800">Admin Login</h1>
            <p class="text-slate-400 text-sm font-bold">Workey কন্ট্রোল প্যানেল</p>
        </div>

        @if(session('error'))
            <div class="bg-rose-50 text-rose-600 p-4 rounded-2xl mb-6 text-sm font-bold border border-rose-100">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-black text-slate-500 uppercase mb-2 ml-1">ফোন নম্বর</label>
                <input type="text" name="phone" required placeholder="017XXXXXXXX" 
                       class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 font-bold text-slate-700">
            </div>

            <div>
                <label class="block text-xs font-black text-slate-500 uppercase mb-2 ml-1">পাসওয়ার্ড</label>
                <input type="password" name="password" required placeholder="••••••••" 
                       class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 font-bold">
            </div>

            <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black shadow-lg hover:bg-black transition-all">
                প্রবেশ করুন
            </button>
        </form>
    </div>
</body>
</html>