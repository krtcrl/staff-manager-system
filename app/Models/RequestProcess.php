<?php

// app/Models/RequestProcess.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestProcess extends Model
{
    protected $fillable = [
        'unique_code',
        'part_number',
        'process_type',
        'process_order'
    ];
    public function request()
{
    return $this->belongsTo(RequestModel::class, 'unique_code', 'unique_code');
}
}