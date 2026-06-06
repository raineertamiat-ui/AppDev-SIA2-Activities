<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\VetController;

/*
|--------------------------------------------------------------------------
| API Routes - PetCare Scheduler State Configuration
|--------------------------------------------------------------------------
|
| The 'web' middleware is explicitly added here to ensure cookie sessions 
| and CSRF states are verified when JavaScript fetch operations contact the API.
|
*/

Route::middleware(['web', 'auth'])->group(function () {

    // --------------------------------------------------------------------------
    // PET MANAGEMENT ENDPOINTS
    // --------------------------------------------------------------------------
    Route::post('/pets', [PetController::class, 'store']);
    Route::get('/pets', [PetController::class, 'myPets']);
    Route::get('/my-pets', [PetController::class, 'myPets']);
    Route::put('/pets/{id}', [PetController::class, 'update']);
    Route::delete('/pets/{id}', [PetController::class, 'destroy']);

    // --------------------------------------------------------------------------
    // CLIENT APPOINTMENT ENDPOINTS
    // --------------------------------------------------------------------------
    Route::post('/appointments', [AppointmentController::class, 'store']); 
    Route::get('/my-appointments', [AppointmentController::class, 'myAppointments']);
    Route::get('/appointments', [AppointmentController::class, 'myAppointments']);
    Route::post('/appointments/{id}/cancel', [AppointmentController::class, 'cancel']);
    Route::get('/auth/vets', [AppointmentController::class, 'listVets']);

    // --------------------------------------------------------------------------
    // CLINIC & VETERINARIAN WORKSPACE ENDPOINTS (Prefixed with /vet)
    // --------------------------------------------------------------------------
    Route::prefix('vet')->group(function () {
        
        // Fetches triage rows
        Route::get('/appointments', [VetController::class, 'index']);

        // Updates appointment states (Pending, Approved, Completed, Rescheduled)
        Route::post('/update-status', [VetController::class, 'updateStatus']);

        // Purges entries from data tables
        Route::delete('/appointments/{id}', [VetController::class, 'destroy']);
    });
    
});