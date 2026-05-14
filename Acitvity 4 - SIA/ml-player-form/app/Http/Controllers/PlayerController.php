<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;

class PlayerController extends Controller {
    public function index() { // [cite: 46]
        $players = Player::latest()->get();
        return view('players.index', compact('players'));
    }

    public function create() { // [cite: 47]
        return view('players.create');
    }

    public function store(Request $request) { // [cite: 48]
        $validated = $request->validate([ // Bonus Validation [cite: 75, 90]
            'ign' => 'required|min:3',
            'email' => 'required|email',
            'hero' => 'required',
            'rank' => 'required',
            'role' => 'required',
            'matches' => 'required|numeric|min:1',
            'reason' => 'required|min:5'
        ]);
        Player::create($validated);
        return redirect('/players')->with('success', 'Profile Created Successfully!');
    }

    public function show(Player $player) { // [cite: 49]
        return view('players.show', compact('player'));
    }

    public function edit(Player $player) { // [cite: 50]
        return view('players.edit', compact('player'));
    }

    public function update(Request $request, Player $player) { // [cite: 51]
        $validated = $request->validate([
            'ign' => 'required', 'email' => 'required|email', 'hero' => 'required',
            'rank' => 'required', 'role' => 'required', 'matches' => 'required|numeric',
            'reason' => 'required'
        ]);
        $player->update($validated);
        return redirect('/players')->with('success', 'Profile Updated!');
    }

    public function destroy(Player $player) { // [cite: 52]
        $player->delete();
        return redirect('/players')->with('success', 'Profile Deleted!');
    }
}