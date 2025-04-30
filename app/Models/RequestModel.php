<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestModel extends Model
{
    use HasFactory;

    protected $table = 'requests';

    protected $fillable = [
        'unique_code', 
        'part_number', 
        'part_name', 
        'process_type',  
        'description', 
        'manager_1_status', 
        'manager_2_status', 
        'manager_3_status', 
        'manager_4_status', 
        'overall_status',
        'attachment',
    //'final_approval_attachment', 
    'is_edited' => 'boolean',
   'staff_id',

        'total_processes', // ✅ Add this
        'current_process_index' // ✅ Add this
    ];
    public $timestamps = true;  // ✅ Ensure timestamps are enabled

    public function managerApprovals()
    {
        return $this->hasMany(ManagerApproval::class, 'unique_code', 'unique_code');
    }
    // app/Models/RequestModel.php
public function staff()
{
    return $this->belongsTo(\App\Models\Staff::class, 'staff_id');
}
}