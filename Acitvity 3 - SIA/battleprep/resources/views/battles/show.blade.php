@extends('layout')

@section('content')
<div class="max-w-2xl mx-auto bg-slate-800 rounded-2xl overflow-hidden border border-slate-700">
    <div class="p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-4xl font-black">{{ $battle->hero_name }}</h1>
            <span class="text-lg font-bold {{ $battle->result == 'Win' ? 'text-green-400' : 'text-red-400' }}">{{ $battle->result }}</span>
        </div>
        
        <div class="space-y-6">
            <div>
                <p class="text-slate-400 text-sm uppercase font-bold">Role</p>
                <p class="text-xl">{{ $battle->role }}</p>
            </div>
            <div>
                <p class="text-slate-400 text-sm uppercase font-bold">Strategy Used</p>
                <p class="text-xl">{{ $battle->strategy }}</p>
            </div>
            <div class="bg-slate-900 p-6 rounded-xl border border-slate-700">
                <p class="text-slate-400 text-sm uppercase font-bold mb-2">Performance Notes</p>
                <p class="italic text-slate-300">"{{ $battle->notes }}"</p>
            </div>
        </div>
    </div>
    <div class="bg-slate-900/50 p-4 flex justify-between px-8">
        <a href="/battles" class="text-slate-400 hover:text-white transition">← Back to History</a>
        <p class="text-slate-500 text-sm">{{ $battle->created_at->diffForHumans() }}</p>
    </div>
</div>
@endsection