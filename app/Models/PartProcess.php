<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartProcess extends Model
{
    use HasFactory;

    protected $table = 'part_processes'; // explicitly specify the table name

    protected $fillable = [
        'part_number',
        'process_type',
        'process_order',
    ];
}
