<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Player extends Model {
    protected $fillable = ['ign', 'email', 'hero', 'rank', 'role', 'matches', 'reason'];
}