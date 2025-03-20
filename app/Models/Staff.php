<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Relationship with RequestHistory
     */
    public function requestHistories(): HasMany
    {
        return $this->hasMany(RequestHistory::class, 'staff_id'); 
    }
}
