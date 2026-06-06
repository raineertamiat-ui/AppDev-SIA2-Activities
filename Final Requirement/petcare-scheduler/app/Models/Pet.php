<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    // Explicitly define the primary key since it's not the default 'id'
    protected $primaryKey = 'pet_id';

    // Matches your exact MySQL table columns from DESCRIBE pets;
    protected $fillable = [
        'owner_id',   // Links directly to user_id on your users table
        'pet_name',
        'type',       // Cat, Dog, etc.
        'breed',
        'birthdate',
        'gender'      // Added based on your database schema grid
    ];

    /**
     * Get the user that owns the pet (semantic name).
     */
    public function owner() 
    {
        return $this->belongsTo(User::class, 'owner_id', 'user_id');
    }

    /**
     * Alias relationship to support controller eager-loading pipelines ($appointment->pet->user)
     * This fixes the empty "No patient records mapped to triage grids" dashboard error.
     */
    public function user() 
    {
        return $this->belongsTo(User::class, 'owner_id', 'user_id');
    }

    /**
     * Get the appointments for the pet.
     */
    public function appointments() 
    {
        return $this->hasMany(Appointment::class, 'pet_id', 'pet_id');
    }
}