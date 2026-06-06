<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'appointment_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'appointment_date', 
        'appointment_time', 
        'service_type', 
        'status', 
        'pet_id', 
        'vet_id'
    ];

    /**
     * Cast attributes to native types for easier data manipulation.
     */
    protected $casts = [
        'appointment_date' => 'date',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    /**
     * Get the pet associated with the appointment.
     */
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class, 'pet_id', 'pet_id');
    }

    /**
     * Get the owner (User) profile of the associated patient pet.
     * Updated to route cleanly through the Pet model to resolve the relation mapping.
     */
    public function user()
    {
        return $this->pet?->user();
    }

    /**
     * Get the veterinarian associated with the appointment.
     * This links to the User model using the vet_id column mapping.
     */
    public function veterinarian(): BelongsTo
    {
        // Updated foreign key links from standard 'id' to match your explicit 'user_id'
        return $this->belongsTo(User::class, 'vet_id', 'user_id');
    }
}