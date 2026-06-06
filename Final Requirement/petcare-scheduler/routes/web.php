<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AppointmentController;

/*
|--------------------------------------------------------------------------
| Web Routes - PetCare Scheduler
|--------------------------------------------------------------------------
|
| This file defines all authentication workflows, HTML view rendering routes,
| and secure JSON endpoints utilized by the frontend asynchronous fetch engines.
|
*/

// =========================================================================
// 1. GUEST ROUTES (Available only to unauthenticated visitors)
// =========================================================================
Route::middleware(['guest'])->group(function () {
    // Login Interface Triggers
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/auth/login', [AuthController::class, 'showLogin']); 
    
    // Identity & Access Requests
    Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
    
    // BACKEND PATH ALIAS: Handles both modern web routes and legacy '/api' registration targets gracefully
    Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/api/auth/register', [AuthController::class, 'register']); 
});

// =========================================================================
// 2. PROTECTED ROUTES (Available only to authenticated sessions)
// =========================================================================
Route::middleware(['auth'])->group(function () {
    
    // --- HTML BLADE ARCHITECTURE VIEWS ---
    Route::get('/user_dashboard', function () {
        return view('user_dashboard');
    })->name('user_dashboard');

    Route::get('/vet_dashboard', function () {
        return view('vet_dashboard');
    })->name('vet_dashboard');


    // --- PET MANAGEMENT ENDPOINTS (Asynchronous JSON APIs) ---
    Route::get('/api/pets', [PetController::class, 'myPets'])->name('pets.index');
    Route::get('/api/my-pets', [PetController::class, 'myPets']); 
    Route::post('/api/pets', [PetController::class, 'store'])->name('pets.store');
    Route::put('/api/pets/{id}', [PetController::class, 'update'])->name('pets.update');
    Route::delete('/api/pets/{id}', [PetController::class, 'destroy'])->name('pets.destroy');


    // --- APPOINTMENT MANAGEMENT ENDPOINTS (Asynchronous JSON APIs) ---
    Route::get('/api/appointments', [AppointmentController::class, 'myAppointments'])->name('appointments.index');
    Route::get('/appointments', [AppointmentController::class, 'myAppointments']); // Browser fallback catch alias
    Route::get('/api/my-appointments', [AppointmentController::class, 'myAppointments']); 
    
    // Reservation pipeline processing destinations
    Route::post('/api/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::post('/appointments', [AppointmentController::class, 'store']); // Catches forms hitting raw base paths
    
    // Cancellation tracking execution
    Route::post('/api/appointments/{id}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::post('/appointments/{id}/cancel', [AppointmentController::class, 'cancel']);


    // --- VETERINARIAN & CLINICAL WORKSPACE SERVICES ---
    // Fetches the veterinarian lists to populate your dropdown selections
    Route::get('/api/auth/vets', [AppointmentController::class, 'listVets'])->name('vets.list');
    Route::get('/api/vet/appointments', [AppointmentController::class, 'clinicIndex'])->name('vet.appointments.index');
    Route::post('/api/vet/update-status', [AppointmentController::class, 'updateStatus'])->name('vet.appointments.updateStatus');


    // --- SESSION TERMINATION ---
    // UPDATED: Combined both endpoints cleanly to hook up into the logout handler seamlessly
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get'); 
});