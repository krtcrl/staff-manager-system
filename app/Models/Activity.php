<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'manager_id',
        'type',
        'description',
        'request_type', // Ensure this is included
        'request_id',   // Add this field
        'expires_at',
    ];
}