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
        return ['database'];  // ✅ Ensure it uses 'database'
    }

    public function toDatabase($notifiable)
    {
        return [
            'request_id' => $this->request->id,
            'message' => 'A new request has been submitted: ' . $this->request->part_number,
            'created_at' => now()->toDateTimeString()
        ];
    }
}
