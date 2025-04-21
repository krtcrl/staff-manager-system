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
        'manager_number',
        'reset_token',               // Added for password reset
        'reset_token_created_at',     // Added for password reset
    ];

    // Fields that should be hidden
    protected $hidden = [
        'password',
        'remember_token',
        'reset_token',                // Hide reset token from serialization
    ];

    // Fields that should be cast
    protected $casts = [
        'email_verified_at' => 'datetime',
        'reset_token_created_at' => 'datetime',  // Cast to Carbon instance
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

    // Ensure this matches your notifications table structure
    public function receivesBroadcastNotificationsOn()
    {
        return 'manager.'.$this->id;
    }

    /**
     * Generate a password reset token
     */
    public function generatePasswordResetToken()
    {
        $token = \Illuminate\Support\Str::random(60);
        $this->forceFill([
            'reset_token' => \Illuminate\Support\Facades\Hash::make($token),
            'reset_token_created_at' => now(),
        ])->save();
        
        return $token;
    }

    /**
     * Clear password reset token
     */
    public function clearPasswordResetToken()
    {
        $this->forceFill([
            'reset_token' => null,
            'reset_token_created_at' => null,
        ])->save();
    }

    /**
     * Route notifications for the mail channel
     */
    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }
}