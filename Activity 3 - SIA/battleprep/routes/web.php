<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BattleController;

// Redirect the main page to our battles list
Route::get('/', function () {
    return redirect('/battles');
});

// One line handles all CRUD routes
Route::resource('battles', BattleController::class);