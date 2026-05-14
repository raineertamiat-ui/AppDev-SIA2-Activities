<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController; // Import your custom controller
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Welcome Page
Route::get('/', function () {
    return view('welcome');
});

/**
 * DASHBOARD INTEGRATION
 * This route satisfies Part 5 of Activity-5. 
 * Instead of a simple view, it calls DashboardController@index to fetch:
 * 1. Logged-in user info
 * 2. Data from your own API
 * 3. Data from a public API
 */
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Admin Route (Part 2 Requirement)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin', function () {
        return view('admin'); // Ensure you have an admin.blade.php if redirecting admins here
    })->name('admin');
});

// Profile Management (Breeze Default)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';