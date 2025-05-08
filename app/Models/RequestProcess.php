<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestProcess extends Model
{
    protected $table = 'request_processes';
    
    protected $fillable = [
        'unique_code',
        'part_number',
        'process_type',
        'process_order',
        'status',
        'remarks'
    ];
    
    public function request()
    {
        return $this->belongsTo(RequestModel::class, 'unique_code', 'unique_code');
    }
    
    public function part()
    {
        return $this->belongsTo(Part::class, 'part_number', 'part_number');
    }
}