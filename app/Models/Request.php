<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Request extends Model
{
    protected $fillable = [
        'unique_code', 'part_number', 'part_name', 'process_type', 
        'uph', 'description', 'revision_type','manager_1_status', 'manager_2_status', 
        'manager_3_status', 'manager_4_status', 'overall_status'
    ];
    

    protected static function boot()
    {
        parent::boot();

        // Automatically create rows for each manager when a request is created
        static::creating(function ($request) {
            $managers = ['manager 1', 'manager 2', 'manager 3', 'manager 4'];

            foreach ($managers as $manager) {
                self::create([
                    'unique_code' => $request->unique_code, // Use the same unique_code
                    'description' => $request->description, // Use the same description
                    'status' => 'pending', // Set default status
                    'manager_id' => $manager, // Assign manager
                ]);
            }
        });
    }
    

    public function index()
    {
        // Fetch all requests from the database
        $requests = Request::select('unique_code', 'description', 'status')->get();

        // Pass the data to the view
        return view('manager.manager_main', compact('requests'));
    }
    protected static function booted()
    {
        static::created(function ($request) {
            event(new \App\Events\NewRequestNotification($request));
        });
    }
    
    
}