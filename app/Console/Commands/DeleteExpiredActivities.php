<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Activity; // Assuming you have an Activity model
use Carbon\Carbon;

class DeleteExpiredActivities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activities:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete activities older than 3 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Calculate the date 3 days ago
        $expiryDate = Carbon::now()->subDays(5);

        // Delete activities older than 3 days
        Activity::where('created_at', '<', $expiryDate)->delete();

        $this->info('Expired activities deleted successfully.');
    }
}