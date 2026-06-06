<?php

namespace App\Models;

// Handles Sanctum API tokens if you decide to use bearer tokens down the line
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The table associated with the model.
     */
    protected $table = 'users';

    /**
     * The primary key associated with the table.
     * Overriding this ensures Auth::id() correctly returns your 'user_id' value.
     */
    protected $primaryKey = 'user_id';

    /**
     * Indicates if the model's ID is auto-incrementing.
     */
    public $incrementing = true;

    /**
     * The data type of the auto-incrementing ID.
     */
    protected $keyType = 'int';

    /**
     * The attributes that are mass fillable.
     * Synchronized with AuthController registration keys.
     */
    protected $fillable = [
        'full_name', 
        'email', 
        'password', 
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password', 
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Auto-hashes password field if using standard Eloquent creation
    ];

    /**
     * Helper check to verify if the user account is a clinic veterinarian.
     */
    public function isVet(): bool
    {
        return strtolower($this->role) === 'veterinarian' || strtolower($this->role) === 'vet';
    }

    /**
     * --------------------------------------------------------------------------
     * Eloquent Relationships
     * --------------------------------------------------------------------------
     */

    /**
     * Get all pets owned by this user.
     * Maps to: Users -> Pets (via owner_id)
     */
    public function pets(): HasMany
    {
        return $this->hasMany(Pet::class, 'owner_id', 'user_id');
    }

    /**
     * Get all appointments for all pets owned by this user.
     * This safe collection engine loads full nested model trees so frontends never experience 
     * undefined pointer failures reading 'appointment.pet.pet_name'.
     */
    public function appointments()
    {
        $petIds = $this->pets()->pluck('pet_id');

        return Appointment::whereIn('pet_id', $petIds)
            ->with(['pet', 'veterinarian'])
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc');
    }

    /**
     * Get all appointments assigned to this user as a Veterinarian.
     * Maps to: Users -> Appointments (via vet_id)
     */
    public function medicalSchedule(): HasMany
    {
        // Custom keys properly specified to avoid fallback collisions with standard Laravel increments
        return $this->hasMany(Appointment::class, 'vet_id', 'user_id');
    }
}