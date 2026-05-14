<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BattlePrep | ML Strategy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 font-sans">
    <nav class="bg-slate-800 border-b border-slate-700 p-4 mb-8">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/battles" class="text-2xl font-black text-cyan-500 tracking-tighter">BATTLEPREP</a>
            <a href="/battles/create" class="bg-cyan-600 hover:bg-cyan-500 px-4 py-2 rounded-lg font-bold transition text-sm">ADD MATCH</a>
        </div>
    </nav>

    <div class="container mx-auto px-4 max-w-5xl">
        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-400 p-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </div>
</body>
</html>