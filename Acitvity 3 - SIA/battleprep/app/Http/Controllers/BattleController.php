<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Battle;

class BattleController extends Controller
{
    public function index() {
        $battles = Battle::latest()->get();
        return view('battles.index', compact('battles'));
    }

    public function create() {
        return view('battles.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'hero_name' => 'required|string|max:255',
            'role' => 'required',
            'strategy' => 'required',
            'result' => 'required',
            'notes' => 'required|min:5'
        ]);

        Battle::create($validated);
        return redirect('/battles')->with('success', 'Match recorded successfully!');
    }

    public function show(Battle $battle) {
        return view('battles.show', compact('battle'));
    }

    public function edit(Battle $battle) {
        return view('battles.edit', compact('battle'));
    }

    public function update(Request $request, Battle $battle) {
        $validated = $request->validate([
            'hero_name' => 'required',
            'role' => 'required',
            'strategy' => 'required',
            'result' => 'required',
            'notes' => 'required'
        ]);

        $battle->update($validated);
        return redirect('/battles')->with('success', 'Battle updated!');
    }

    public function destroy(Battle $battle) {
        $battle->delete();
        return redirect('/battles')->with('success', 'Record deleted.');
    }
}