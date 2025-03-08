<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Manager;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Get the first manager
        $manager = Manager::first();

        // Create sample activities
        Activity::create([
            'manager_id' => $manager->id,
            'type' => 'request',
            'description' => 'New request created by John Doe',
        ]);

        Activity::create([
            'manager_id' => $manager->id,
            'type' => 'approval',
            'description' => 'Request #123 approved',
        ]);
    }
}