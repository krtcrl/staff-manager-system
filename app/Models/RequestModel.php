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
        'uph', 
        'description', 
        'revision_type',
        'manager_1_status', 
        'manager_2_status', 
        'manager_3_status', 
        'manager_4_status', 
        'overall_status',
        'attachment',
        'bottle_neck_uph',
    'final_approval_attachment', 
        'standard_yield_percentage', // Add this
        'standard_yield_dollar_per_hour', // Add this
        'actual_yield_percentage', // Add this
        'actual_yield_dollar_per_hour', // Add this
        'total_processes', // ✅ Add this
        'current_process_index' // ✅ Add this
    ];
    public $timestamps = true;  // ✅ Ensure timestamps are enabled

    public function managerApprovals()
    {
        return $this->hasMany(ManagerApproval::class, 'unique_code', 'unique_code');
    }
}