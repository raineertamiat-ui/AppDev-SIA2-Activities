@extends('layout')
@section('content')
<div class="max-w-xl mx-auto bg-slate-800 p-8 rounded-3xl border border-slate-700 shadow-2xl">
    <h2 class="text-2xl font-bold mb-8">Setup Player Profile</h2>
    <form action="/players" method="POST" class="space-y-4">
        @csrf
        <input type="text" name="ign" placeholder="IGN" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 outline-none">
        <input type="email" name="email" placeholder="Email Address" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3">
        <div class="grid grid-cols-2 gap-4">
            <input type="text" name="hero" placeholder="Favorite Hero" class="bg-slate-900 border border-slate-700 rounded-xl p-3">
            <select name="rank" class="bg-slate-900 border border-slate-700 rounded-xl p-3">
                <option value="Epic">Epic</option>
                <option value="Legend">Legend</option>
                <option value="Mythic">Mythic</option>
            </select>
        </div>
        <input type="text" name="role" placeholder="Role (e.g. Assassin)" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3">
        <input type="number" name="matches" placeholder="Total Matches" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3">
        <textarea name="reason" placeholder="Why do you like this hero?" rows="3" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3"></textarea>
        <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-500 py-4 rounded-2xl font-bold transition">SAVE PROFILE</button>
    </form>
</div>
@endsection