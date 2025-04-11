<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_code',
        'manager_id',
        'action',
        'description',
    ];

    // Relationships (optional, for convenience)
    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }

    public function request()
    {
        return $this->belongsTo(RequestModel::class, 'unique_code', 'unique_code');
    }
}
