<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Manager ID
        'type',
        'message',
        'read',
    ];

    public function manager()
    {
        return $this->belongsTo(Manager::class, 'user_id');
    }
}
