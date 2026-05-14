<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | ML Profile Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Rajdhani', sans-serif; background-color: #0f172a; color: #f8fafc; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body>

    <nav class="border-b border-slate-800 bg-[#1e293b]/80 px-6 py-4 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-400 tracking-widest uppercase italic">ML PROFILE HUB</h1>
            <div class="flex items-center gap-6">
                <div class="text-right">
                    <p class="text-white font-bold uppercase leading-none">{{ $currentUser->name }}</p>
                    <p class="text-blue-400 text-xs tracking-widest uppercase">{{ $currentUser->role }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-red-500 border border-red-500/30 px-3 py-1 rounded hover:bg-red-500 hover:text-white transition-all uppercase">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-8 space-y-12">
        
        <section>
            <h2 class="text-xl font-bold text-white mb-6 uppercase tracking-widest flex items-center gap-3">
                <span class="w-2 h-6 bg-blue-500"></span> Registered Players (Internal)
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($myUsers as $user)
                <div class="glass p-5 rounded-2xl border-l-4 border-blue-500 hover:bg-white/5 transition-all">
                    <p class="text-white font-bold text-lg">{{ $user->name }}</p>
                    <p class="text-slate-500 text-xs">{{ $user->email }}</p>
                </div>
                @endforeach
            </div>
        </section>

        <section>
            <h2 class="text-xl font-bold text-white mb-6 uppercase tracking-widest flex items-center gap-3">
                <span class="w-2 h-6 bg-purple-500"></span> Global Meta Updates (External)
            </h2>
            <div class="glass rounded-3xl overflow-hidden shadow-2xl">
                <table class="w-full text-left">
                    <thead class="bg-blue-600/10 text-blue-400 text-xs uppercase tracking-widest">
                        <tr>
                            <th class="px-8 py-5">Update Topic</th>
                            <th class="px-8 py-5">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @foreach($externalPosts as $post)
                        <tr class="hover:bg-blue-500/5 transition-all">
                            <td class="px-8 py-5 font-bold text-blue-200 uppercase">{{ $post['title'] }}</td>
                            <td class="px-8 py-5 text-slate-400 text-sm leading-relaxed">{{ $post['body'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

    </main>
</body>
</html>