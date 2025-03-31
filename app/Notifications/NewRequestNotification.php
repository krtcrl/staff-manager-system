<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $request;
    public $url;  // ✅ Add URL property

    public function __construct($request, $url)
    {
        $this->request = $request;
        $this->url = $url;  // ✅ Set URL
    }

    // Use 'database' channel
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Request Created',  
            'request_id' => $this->request->id,
            'message' => 'A new request has been submitted: ' . $this->request->part_number,
            'url' => $this->url,  // ✅ Include the clickable URL
            'created_at' => now()->toDateTimeString()
        ];
    }
}
