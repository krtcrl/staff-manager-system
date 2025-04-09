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
        'part_name',  // Added this line
        'description',
        'status',
        'manager_1_status',
        'manager_2_status',
        'manager_3_status',
        'manager_4_status',
        'manager_5_status',
        'manager_6_status',
        'staff_id',  // Make sure this is included
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