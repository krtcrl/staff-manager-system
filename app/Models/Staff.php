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
    ];

    protected $hidden = [
        'password',
        'remember_token',
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
}