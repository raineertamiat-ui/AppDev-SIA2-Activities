@extends('layout')

@section('content')
<div class="max-w-xl mx-auto bg-slate-800 p-8 rounded-2xl shadow-xl">
    <h2 class="text-2xl font-bold mb-6">Log New Match</h2>
    <form action="/battles" method="POST" class="space-y-4">
        @csrf
        <input type="text" name="hero_name" placeholder="Hero Name" class="w-full bg-slate-900 border border-slate-700 p-3 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">
        
        <select name="role" class="w-full bg-slate-900 border border-slate-700 p-3 rounded-lg outline-none">
            <option value="Tank">Tank</option>
            <option value="Fighter">Fighter</option>
            <option value="Assassin">Assassin</option>
            <option value="Mage">Mage</option>
            <option value="Marksman">Marksman</option>
            <option value="Support">Support</option>
        </select>

        <input type="text" name="strategy" placeholder="Strategy Used (e.g. Split Push)" class="w-full bg-slate-900 border border-slate-700 p-3 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none">

        <select name="result" class="w-full bg-slate-900 border border-slate-700 p-3 rounded-lg outline-none">
            <option value="Win">Win</option>
            <option value="Loss">Loss</option>
        </select>

        <textarea name="notes" placeholder="What to improve?" rows="4" class="w-full bg-slate-900 border border-slate-700 p-3 rounded-lg focus:ring-2 focus:ring-cyan-500 outline-none"></textarea>

        <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-500 py-3 rounded-lg font-bold transition">SAVE BATTLE</button>
    </form>
</div>
@endsection