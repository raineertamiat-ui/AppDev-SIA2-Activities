<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;

Route::get('/', function () { return redirect('/players'); });
Route::resource('players', PlayerController::class);