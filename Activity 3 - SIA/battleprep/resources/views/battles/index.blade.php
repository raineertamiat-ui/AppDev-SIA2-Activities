@extends('layout')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($battles as $battle)
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 hover:border-cyan-500/50 transition">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-xl font-bold">{{ $battle->hero_name }}</h3>
                <p class="text-slate-400 text-xs uppercase">{{ $battle->role }}</p>
            </div>
            <span class="px-2 py-1 rounded text-xs font-bold {{ $battle->result == 'Win' ? 'bg-green-900 text-green-400' : 'bg-red-900 text-red-400' }}">
                {{ $battle->result }}
            </span>
        </div>
        <div class="flex gap-3 border-t border-slate-700 pt-4 mt-4">
            <a href="/battles/{{ $battle->id }}" class="text-sm text-cyan-400 hover:underline">View</a>
            <a href="/battles/{{ $battle->id }}/edit" class="text-sm text-slate-400 hover:underline">Edit</a>
            <form action="/battles/{{ $battle->id }}" method="POST" onsubmit="return confirm('Delete this record?')">
                @csrf @method('DELETE')
                <button class="text-sm text-red-500 hover:underline">Delete</button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endsection