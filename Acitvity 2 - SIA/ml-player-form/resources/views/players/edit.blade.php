@extends('layout')
@section('content')
<div class="max-w-xl mx-auto bg-slate-800 p-8 rounded-3xl border border-slate-700 shadow-2xl">
    <h2 class="text-2xl font-bold mb-8 text-cyan-400">Update Profile: {{ $player->ign }}</h2>
    <form action="/players/{{ $player->id }}" method="POST" class="space-y-5">
        @csrf @method('PUT')
        
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">In-Game Name</label>
            <input type="text" name="ign" value="{{ $player->ign }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 outline-none focus:ring-2 focus:ring-cyan-500 transition">
        </div>

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Current Rank</label>
                <select name="rank" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 outline-none">
                    @foreach(['Warrior','Elite','Master','Grandmaster','Epic','Legend','Mythic'] as $r)
                        <option value="{{ $r }}" {{ $player->rank == $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Favorite Hero</label>
                <input type="text" name="hero" value="{{ $player->hero }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3">
            </div>
        </div>

        <input type="hidden" name="email" value="{{ $player->email }}">
        <input type="hidden" name="role" value="{{ $player->role }}">
        <input type="hidden" name="matches" value="{{ $player->matches }}">

        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Strategy/Notes</label>
            <textarea name="reason" rows="4" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3">{{ $player->reason }}</textarea>
        </div>

        <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-500 py-4 rounded-2xl font-bold transition shadow-lg shadow-cyan-900/20 uppercase tracking-widest">Update Record</button>
    </form>
</div>
@endsection