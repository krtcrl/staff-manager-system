<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;

    protected $fillable = ['part_number', 'part_name']; // Allow mass assignment
    public function processes()
{
    return $this->hasMany(PartProcess::class, 'part_number', 'part_number');
}
}
