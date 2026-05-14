<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ML Profile Hub | Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Rajdhani', sans-serif; background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%); }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center text-white">

    @if (Route::has('login'))
        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
            @auth
                <a href="{{ url('/dashboard') }}" class="font-semibold text-blue-400 hover:text-white transition-all uppercase tracking-widest">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="font-semibold text-slate-400 hover:text-white transition-all uppercase tracking-widest">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="ml-4 font-semibold text-slate-400 hover:text-white transition-all uppercase tracking-widest">Register</a>
                @endif
            @endauth
        </div>
    @endif

    <div class="text-center">
        <h1 class="text-6xl font-bold tracking-tighter italic text-blue-500 mb-4">ML PROFILE HUB</h1>
        <p class="text-slate-400 uppercase tracking-[0.5em] text-sm">System Integration Analytics</p>
    </div>

</body>
</html>