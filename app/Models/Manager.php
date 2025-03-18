<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Events\NewRequestNotification;

class Manager extends Authenticatable
{
    use Notifiable;

    // Ensure it references the correct table
    protected $table = 'managers';

    // Fields that can be mass-assigned
    protected $fillable = [
        'name',
        'email',
        'password',
        'manager_number', // Add this line
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

    // Define the relationship with the FinalManager model (if applicable)
    public function finalManager()
    {
        return $this->hasOne(FinalManager::class);
    }
}