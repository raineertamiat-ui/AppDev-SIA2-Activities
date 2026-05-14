<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

// Test this URL in your browser: http://127.0.0.1:8000/api/users
Route::get('/users', [UserController::class, 'index']);