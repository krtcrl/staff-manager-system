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
        'revision_type',
        'uph',
        'description',
        'attachment',
        'manager_1_status',
        'manager_2_status',
        'manager_3_status',
        'manager_4_status',
        'manager_5_status',
        'manager_6_status',
        'process_type',
        'standard_yield_percentage',         // ✅ Add new columns
        'standard_yield_dollar_per_hour',
        'actual_yield_percentage',
        'actual_yield_dollar_per_hour',
        'current_process_index',
        'total_processes',
        'final_approval_attachment',    // New column
        'bottle_neck_uph',  
        'created_at',
        'updated_at',
    ];
}