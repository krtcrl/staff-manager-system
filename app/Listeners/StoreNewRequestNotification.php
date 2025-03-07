<?php

namespace App\Listeners;

use App\Events\NewRequestNotification;
use App\Models\Notification; // Assuming you have a Notification model

class StoreNewRequestNotification
{
    /**
     * Handle the event.
     *
     * @param NewRequestNotification $event
     * @return void
     */
    public function handle(NewRequestNotification $event)
    {
        // Store the notification in the database
        Notification::create([
            'message' => 'New request created: ' . $event->request->unique_code,
            'request_id' => $event->request->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}