<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    /**
     * 1. GET /api/pets (or /api/my-pets)
     * Fetches saved pets belonging specifically to the logged-in user.
     */
    public function myPets(Request $request)
    {
        // Dynamically handles both standard web sessions and token-driven user lookups
        $ownerId = Auth::id() ?? ($request->user() ? $request->user()->user_id : null);

        if (!$ownerId) {
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'Your authentication session has expired.'
            ], 401);
        }

        $pets = Pet::where('owner_id', $ownerId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Enforces structural array nesting rules expected by frontend loops
        return response()->json([
            'success' => true,
            'pets'    => $pets
        ]);
    }

    /**
     * 2. POST /api/pets
     * Store a newly created pet profile.
     */
    public function store(Request $request)
    {
        // 1. Accepts polymorphic keys regardless of frontend or database naming variations
        $validated = $request->validate([
            'name'      => ['nullable', 'string', 'max:255'],
            'pet_name'  => ['nullable', 'string', 'max:255'],
            'species'   => ['nullable', 'string'],
            'type'      => ['nullable', 'string'],
            'breed'     => ['required', 'string', 'max:255'],
            'birthday'  => ['nullable', 'date'],
            'birthdate' => ['nullable', 'date'],
            'gender'    => ['required', 'string'],
        ]);

        $ownerId = Auth::id() ?? ($request->user() ? $request->user()->user_id : null);

        if (!$ownerId) {
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'Your session has expired. Please log in again.'
            ], 401);
        }

        // 2. Maps payloads across model fillable schemas safely
        $pet = Pet::create([
            'owner_id'  => $ownerId,
            'pet_name'  => $request->input('pet_name') ?? $request->input('name'),
            'type'      => $request->input('type') ?? $request->input('species'),
            'breed'     => $validated['breed'],
            'birthdate' => $request->input('birthdate') ?? $request->input('birthday'),
            'gender'    => $validated['gender'],
        ]);

        return response()->json([
            'success' => true,
            'status'  => 'success',
            'message' => 'Pet profile registered successfully!',
            'pet'     => $pet
        ], 201);
    }

    /**
     * 3. PUT /api/pets/{id}
     * Update an existing pet profile's details.
     */
    public function update(Request $request, $id)
    {
        $ownerId = Auth::id() ?? ($request->user() ? $request->user()->user_id : null);

        // Security Guard: Enforces scope query limiting so data can't be updated across users
        $pet = Pet::where('pet_id', $id)->where('owner_id', $ownerId)->first();

        if (!$pet) {
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'Pet profile records not found or action unauthorized.'
            ], 404);
        }

        $validated = $request->validate([
            'pet_name'  => ['required', 'string', 'max:255'],
            'type'      => ['required', 'string'],
            'breed'     => ['required', 'string'],
            'birthdate' => ['required', 'date'],
            'gender'    => ['required', 'string'],
        ]);

        $pet->update($validated);

        return response()->json([
            'success' => true,
            'status'  => 'success',
            'message' => 'Pet profile modified successfully!',
            'pet'     => $pet
        ]);
    }

    /**
     * 4. DELETE /api/pets/{id}
     * Delete an owned pet profile.
     */
    public function destroy(Request $request, $id)
    {
        $ownerId = Auth::id() ?? ($request->user() ? $request->user()->user_id : null);

        // Security Guard: Restricts profile destruction explicitly to the true account owner
        $pet = Pet::where('pet_id', $id)->where('owner_id', $ownerId)->first();

        if (!$pet) {
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'Record verification mapping exception failed.'
            ], 404);
        }

        $pet->delete();

        return response()->json([
            'success' => true,
            'status'  => 'success',
            'message' => 'Pet profile removed successfully.'
        ]);
    }
}