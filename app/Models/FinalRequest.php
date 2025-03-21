<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalRequest extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'finalrequests';

    // Specify the fields that are mass assignable
    protected $fillable = [
        'unique_code',
        'part_number',
        'part_name',
        'description',
        'attachment',
        'manager_1_status',
        'manager_2_status',
        'manager_3_status',
        'manager_4_status',
        'manager_5_status',
        'manager_6_status',
        'process_type',
        'current_process_index',
        'total_processes',
        'final_approval_attachment',    // New column
        'created_at',
        'updated_at',
    ];
}