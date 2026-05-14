<!DOCTYPE html>
<html>
<head>
    <title>ML Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white p-8">
    <nav class="max-w-5xl mx-auto flex justify-between items-center mb-10 border-b border-slate-700 pb-5">
        <a href="/players" class="text-3xl font-black text-cyan-500 tracking-tighter hover:text-cyan-400">ML PROFILE HUB</a>
        <a href="/players/create" class="bg-cyan-600 hover:bg-cyan-500 px-6 py-2 rounded-full font-bold">ADD PLAYER</a>
    </nav>
    <div class="max-w-5xl mx-auto">
        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-400 p-4 rounded-xl mb-8">{{ session('success') }}</div>
        @endif
        @yield('content')
    </div>
</body>
</html>