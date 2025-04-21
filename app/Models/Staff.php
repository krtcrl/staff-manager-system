<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Staff extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'reset_token',               // Added for password reset
        'reset_token_created_at',     // Added for password reset
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'reset_token',                // Hide reset token from serialization
    ];

    protected $casts = [
        'reset_token_created_at' => 'datetime',  // Cast to Carbon instance
    ];

    /**
     * Relationship with RequestHistory
     */
    public function requestHistories(): HasMany
    {
        return $this->hasMany(RequestHistory::class, 'staff_id');
    }

    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }
    
    /**
     * Relationship with notifications
     */
    public function notifications()
    {
        return $this->morphMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable')
                   ->orderBy('created_at', 'desc');
    }

    /**
     * Relationship with unread notifications
     */
    public function unreadNotifications()
    {
        return $this->morphMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable')
                   ->whereNull('read_at')
                   ->orderBy('created_at', 'desc');
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
}