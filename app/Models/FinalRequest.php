<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalRequest extends Model
{
    use HasFactory;

    protected $table = 'finalrequests';

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
        'process_type',
        'current_process_index',
        'total_processes'
    ];
}
