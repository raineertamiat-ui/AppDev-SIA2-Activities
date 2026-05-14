@extends('layout')

@section('content')
<div class="max-w-xl mx-auto bg-slate-800 p-8 rounded-2xl shadow-xl">
    <h2 class="text-2xl font-bold mb-6 text-yellow-500">Edit Battle Record</h2>
    <form action="/battles/{{ $battle->id }}" method="POST" class="space-y-4">
        @csrf @method('PUT')
        
        <label class="text-xs text-slate-400">Hero Name</label>
        <input type="text" name="hero_name" value="{{ $battle->hero_name }}" class="w-full bg-slate-900 border border-slate-700 p-3 rounded-lg">
        
        <label class="text-xs text-slate-400">Role</label>
        <select name="role" class="w-full bg-slate-900 border border-slate-700 p-3 rounded-lg">
            @foreach(['Tank','Fighter','Assassin','Mage','Marksman','Support'] as $role)
                <option value="{{ $role }}" {{ $battle->role == $role ? 'selected' : '' }}>{{ $role }}</option>
            @endforeach
        </select>

        <label class="text-xs text-slate-400">Strategy</label>
        <input type="text" name="strategy" value="{{ $battle->strategy }}" class="w-full bg-slate-900 border border-slate-700 p-3 rounded-lg">

        <label class="text-xs text-slate-400">Result</label>
        <select name="result" class="w-full bg-slate-900 border border-slate-700 p-3 rounded-lg">
            <option value="Win" {{ $battle->result == 'Win' ? 'selected' : '' }}>Win</option>
            <option value="Loss" {{ $battle->result == 'Loss' ? 'selected' : '' }}>Loss</option>
        </select>

        <label class="text-xs text-slate-400">Notes</label>
        <textarea name="notes" rows="4" class="w-full bg-slate-900 border border-slate-700 p-3 rounded-lg">{{ $battle->notes }}</textarea>

        <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-500 py-3 rounded-lg font-bold transition">UPDATE RECORD</button>
    </form>
</div>
@endsection