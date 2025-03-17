<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalRequest extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'finalrequests';

    // Define fillable fields
    protected $fillable = [
        'unique_code',
        'part_number',
        'description',
        'process_type',
        'current_process_index',
        'total_processes',
        'manager_1_status',
        'manager_2_status',
        'manager_3_status',
        'manager_4_status',
    ];
}