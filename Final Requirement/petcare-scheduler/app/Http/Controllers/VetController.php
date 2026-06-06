<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\User;

class VetController extends Controller
{
    /**
     * 1. GET /api/vet/appointments
     * Pull comprehensive clinical triage dashboards.
     */
    public function index()
    {
        $user = Auth::user();

        // Enforce role-based access control checking for clinic personnel
        if (!$user || !in_array(strtolower($user->role), ['vet', 'veterinarian', 'admin'])) {
            return response()->json(['message' => 'Unauthorized privilege level mapping exception.'], 403);
        }

        // Fetch all clinic appointments ordered chronologically by scheduled date and time slot
        $allAppointments = Appointment::with(['pet.user', 'veterinarian'])
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        return response()->json($allAppointments, 200);
    }

    /**
     * 2. POST /api/vet/update-status
     * Handles administrative status modifications and claims appointments for the active vet.
     */
    public function updateStatus(Request $request)
    {
        $user = Auth::user();

        if (!$user || !in_array(strtolower($user->role), ['vet', 'veterinarian', 'admin'])) {
            return response()->json(['message' => 'Action unauthorized.'], 403);
        }

        $request->validate([
            'appointment_id' => 'required|exists:appointments,appointment_id',
            'status'         => 'required|string|in:Pending,Approved,Completed,Cancelled,Rescheduled',
            'vet_id'         => 'nullable|exists:users,user_id' 
        ]);

        $appointment = Appointment::where('appointment_id', $request->appointment_id)->first();
        
        if (!$appointment) {
            return response()->json(['message' => 'The target appointment record could not be found.'], 404);
        }
        
        // Dynamic Claiming System Check:
        if (strtolower($request->status) === 'approved') {
            $currentUserId = $user->user_id ?? $user->id;

            // Guard Rule: If already claimed by another doctor, block accidental overrides
            if (!empty($appointment->vet_id) && $appointment->vet_id != $currentUserId) {
                return response()->json([
                    'message' => 'This consultation slot has already been claimed by another medical professional.'
                ], 409);
            }

            // Assign the claiming veterinarian's unique identifier directly to the appointment record
            $appointment->vet_id = $currentUserId;
        } elseif ($request->has('vet_id')) {
            // Fallback for manual administrative structural modifications
            $appointment->vet_id = $request->vet_id;
        }

        $appointment->status = $request->status;
        $appointment->save();

        // Reload relationships so the updated profile data returns instantly to the frontend UI
        $appointment->load(['pet.user', 'veterinarian']);

        return response()->json([
            'status'      => 'success',
            'message'     => 'Appointment documentation ledger status updated and claimed successfully.',
            'appointment' => $appointment
        ], 200);
    }

    /**
     * 3. DELETE /api/vet/appointments/{id}
     * Deletes a record from the database completely upon verified vet confirmation.
     */
    public function destroy($id)
    {
        $user = Auth::user();

        // Security check: Only authenticated vets or admins can destroy schedule entries
        if (!$user || !in_array(strtolower($user->role), ['vet', 'veterinarian', 'admin'])) {
            return response()->json(['message' => 'Action unauthorized.'], 403);
        }

        $appointment = Appointment::where('appointment_id', $id)->first();

        if (!$appointment) {
            return response()->json(['message' => 'The target appointment record could not be found.'], 404);
        }

        $appointment->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Appointment was successfully approved for cancellation and removed from clinical logs.'
        ], 200);
    }
}