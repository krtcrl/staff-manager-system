<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class FinalManager extends Authenticatable
{
    use Notifiable;

    // Ensure it references the correct table
    protected $table = 'finalmanagers';

    // Fields that can be mass-assigned
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Fields that should be hidden
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Fields that should be cast
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Define the relationship with the User model (if applicable)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with the Manager model (if applicable)
    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }
}