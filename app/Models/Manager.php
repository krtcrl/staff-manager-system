<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Events\NewRequestNotification;

class Manager extends Authenticatable
{
    use Notifiable;

    protected $table = 'managers'; // Ensure it references the correct table

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
        // Define the belongsTo relationship with the Manager model
        public function manager()
        {
            return $this->belongsTo(Manager::class);
        }
}
