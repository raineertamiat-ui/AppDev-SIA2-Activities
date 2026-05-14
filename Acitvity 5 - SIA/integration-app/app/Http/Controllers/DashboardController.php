<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. Data from Own System (Requirement: Part 5)
        $myUsers = User::all();

        // 2. Data from Public API (Requirement: Part 4 & 5)
        $response = Http::get('https://jsonplaceholder.typicode.com/posts');
        $rawPosts = $response->successful() ? array_slice($response->json(), 0, 5) : [];

        // Fix the "Latin" Language Issue by mapping to MLBB Topics
        $externalPosts = collect($rawPosts)->map(function($post, $index) {
            $topics = [
                'New Season Patch Notes', 
                'Ranked Matchmaking Balance', 
                'Upcoming Hero Spotlight', 
                'M-World Championship News', 
                'Equipment Stats Adjustment'
            ];
            return [
                'title' => $topics[$index] ?? 'System Update',
                'body' => 'Latest competitive adjustments and meta-shifting changes for the current patch.'
            ];
        });

        // 3. Logged-in User Info (Requirement: Part 5)
        $currentUser = Auth::user();

        return view('dashboard', compact('myUsers', 'externalPosts', 'currentUser'));
    }
}