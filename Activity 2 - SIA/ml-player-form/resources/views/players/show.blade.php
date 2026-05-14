@extends('layout')
@section('content')
<div class="max-w-xl mx-auto bg-slate-800 p-10 rounded-3xl border border-slate-700 shadow-2xl">
    <div class="flex justify-between items-start mb-6">
        <h2 class="text-4xl font-black text-white">{{ $player->ign }}</h2>
        <span class="text-cyan-500 font-bold uppercase tracking-tighter">{{ $player->rank }}</span>
    </div>
    <div class="space-y-4 mb-8">
        <p><span class="text-slate-500 uppercase text-xs font-bold mr-2">Hero:</span> {{ $player->hero }}</p>
        <p><span class="text-slate-500 uppercase text-xs font-bold mr-2">Role:</span> {{ $player->role }}</p>
        <p><span class="text-slate-500 uppercase text-xs font-bold mr-2">Matches:</span> {{ $player->matches }}</p>
        <div class="bg-slate-900/50 p-5 rounded-2xl border border-slate-700 italic text-slate-300">
            "{{ $player->reason }}"
        </div>
    </div>
    <a href="/players" class="inline-block text-cyan-500 hover:underline">← Back to Hub</a>
</div>
@endsection