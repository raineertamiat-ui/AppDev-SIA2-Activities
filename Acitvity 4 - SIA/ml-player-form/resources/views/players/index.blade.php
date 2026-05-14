@extends('layout')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach($players as $player)
    <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-xl">
        <h2 class="text-xl font-bold text-cyan-400 mb-1">{{ $player->ign }}</h2>
        <p class="text-slate-400 text-sm mb-4 uppercase tracking-widest font-bold">{{ $player->rank }}</p>
        <div class="flex gap-3 border-t border-slate-700 pt-4">
            <a href="/players/{{ $player->id }}" class="text-sm text-slate-300 hover:text-cyan-400">View</a>
            <a href="/players/{{ $player->id }}/edit" class="text-sm text-slate-300 hover:text-yellow-400">Edit</a>
            <form action="/players/{{ $player->id }}" method="POST" onsubmit="return confirm('Delete this profile?')">
                @csrf @method('DELETE')
                <button class="text-sm text-red-500 hover:text-red-400">Delete</button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endsection