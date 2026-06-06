<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\User;

class AppointmentController extends Controller
{
    /**
     * 1. GET /appointments (or /api/my-appointments)
     * Fetch all upcoming and historical appointments belonging to the logged-in user's pets.
     */
    public function myAppointments()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized access session.'], 401);
        }

        // Leveraging eager loading via Eloquent relations for clean performance
        $appointments = Appointment::with(['pet' => function($query) {
                // FIXED: 'owner_id' MUST be selected so Eloquent can map the relationship to the user model
                $query->select('pet_id', 'owner_id', 'pet_name', 'breed', 'type');
            }])
            ->whereHas('pet', function ($query) use ($user) {
                $query->where('owner_id', $user->user_id); 
            })
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get()
            ->map(function ($appointment) {
                // Flatten structural values cleanly for dashboard components
                return [
                    'appointment_id'   => $appointment->appointment_id,
                    'pet_id'           => $appointment->pet_id,
                    'pet_name'         => $appointment->pet?->pet_name ?? 'Unknown Pet',
                    'appointment_date' => $appointment->appointment_date ? $appointment->appointment_date->format('Y-m-d') : null,
                    'appointment_time' => $appointment->appointment_time,
                    'service_type'     => $appointment->service_type,
                    'status'           => $appointment->status,
                ];
            });

        // Wrapped cleanly inside an array property matching your frontend grid expectations
        return response()->json($appointments);
    }

    /**
     * 2. POST /appointments
     * Store and secure a brand new client appointment window request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pet_id'           => 'required|exists:pets,pet_id',
            'service_type'     => 'required|string|max:255',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
        ]);

        // Explicit security verification check: Ensure this pet actually belongs to the user
        $petOwnershipVerification = Pet::where('pet_id', $request->pet_id)
            ->where('owner_id', Auth::user()->user_id)
            ->exists();

        if (!$petOwnershipVerification) {
            return response()->json([
                'status'  => 'error', 
                'message' => 'Access Denied. You are not authorized to schedule appointments for this profile entry.'
            ], 403);
        }

        // Initialize and persist record using Mass Assignment ($fillable)
        $appointment = Appointment::create([
            'pet_id'           => $request->pet_id,
            'service_type'     => $request->service_type,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'status'           => 'Pending', // Default state fallback 
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Appointment scheduled successfully!',
            'appointment' => $appointment
        ], 201);
    }

    /**
     * 3. POST /appointments/{id}/cancel
     * Allows a client to mark their appointment as cancelled securely.
     */
    public function cancel($id)
    {
        // Enforces constraints so users can only cancel their own items
        $appointment = Appointment::where('appointment_id', $id)
            ->whereHas('pet', function($query) {
                $query->where('owner_id', Auth::user()->user_id);
            })->first();

        if (!$appointment) {
            return response()->json([
                'status'  => 'error', 
                'message' => 'The request records matching that ID could not be resolved or authorization failed.'
            ], 404);
        }

        $appointment->status = 'Cancelled';
        $appointment->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Your scheduled appointment has been successfully marked as cancelled.'
        ]);
    }

    /**
     * 4. GET /api/auth/vets
     * Pulls list of all available clinic users assigned as Veterinarian workers.
     */
    public function listVets()
    {
        $vets = User::whereIn('role', ['vet', 'veterinarian'])
            ->select('user_id', 'full_name', 'email') 
            ->get();

        return response()->json(['vets' => $vets]);
    }

    /**
     * 5. GET /api/vet/appointments
     * Pull comprehensive clinical triage dashboards (for medical staff triage management views).
     */
    public function clinicIndex()
    {
        $user = Auth::user();

        // Ensure only medical staff can view general dashboard logs
        if (!$user || !in_array(strtolower($user->role), ['vet', 'veterinarian', 'admin'])) {
            return response()->json(['message' => 'Unauthorized privilege level mapping exception.'], 403);
        }

        // Pull appointments with full relational trees safely resolved
        $allAppointments = Appointment::with(['pet.user', 'veterinarian'])
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        return response()->json($allAppointments);
    }

    /**
     * 6. POST /api/vet/update-status
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

        $appointment = Appointment::where('appointment_id', $request->appointment_id)->firstOrFail();
        
        // Dynamic Claiming System Check:
        if (strtolower($request->status) === 'approved') {
            if (!empty($appointment->vet_id) && $appointment->vet_id != $user->user_id) {
                return response()->json([
                    'message' => 'This consultation slot has already been claimed by another medical professional.'
                ], 409);
            }

            // Assign the claiming veterinarian's user_id directly
            $appointment->vet_id = $user->user_id;
        } elseif ($request->has('vet_id')) {
            $appointment->vet_id = $request->vet_id;
        }

        $appointment->status = $request->status;
        $appointment->save();

        // Reload relationships so the updated profile data returns instantly to the frontend UI
        $appointment->load(['pet.user', 'veterinarian']);

        return response()->json([
            'status'  => 'success',
            'message' => 'Appointment documentation ledger status updated and claimed successfully.',
            'appointment' => $appointment
        ]);
    }

    /**
     * 7. DELETE /api/vet/appointments/{id}
     * Deletes a record from the database completely upon verified vet confirmation.
     */
    public function destroy($id)
    {
        $user = Auth::user();

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