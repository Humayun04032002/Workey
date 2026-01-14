<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workey - Employer App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Hind Siliguri', sans-serif; -webkit-tap-highlight-color: transparent; }
        .nav-blur { backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#F8FAFC] pb-24" x-data="{ openNotifications: false }">

    {{-- Main Content Area --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- Include the Navigation Partial --}}
    @include('partials.employer_nav')

</body>
</html>