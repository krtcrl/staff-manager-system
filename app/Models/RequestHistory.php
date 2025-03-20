<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestHistory extends Model
{
    use HasFactory;

    protected $table = 'request_histories';

    protected $fillable = [
        'unique_code',
        'part_number',
        'description',
        'status',
        'manager_1_status',
        'manager_2_status',
        'manager_3_status',
        'manager_4_status',
        'manager_5_status',
        'manager_6_status',
        'completed_at',
    ];

    /**
     * Relationship with Staff
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
