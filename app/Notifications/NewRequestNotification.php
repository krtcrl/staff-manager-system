<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    // Ensure database channel is enabled
    public function via($notifiable)
    {
        return ['database'];  // ✅ Use 'database' channel
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Request Created',  // ✅ Added title
            'request_id' => $this->request->id,
            'message' => 'A new request has been submitted: ' . $this->request->part_number,
            'created_at' => now()->toDateTimeString()
        ];
    }
}
