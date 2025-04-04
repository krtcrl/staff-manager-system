<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $request;
    public $url;

    public function __construct($request, $url)
    {
        $this->request = $request;
        $this->url = $url;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Request Created',
            'message' => 'A new request has been submitted: ' . $this->request->part_number,
            'url' => $this->url,
            'type' => 'new_request', // Specific type for new requests
            'timestamp' => now()->toDateTimeString(),
            'icon' => $this->getIconForType('new_request'),
            'request_id' => $this->request->id // Keeping your original request ID field
        ];
    }

    protected function getIconForType(string $type): string
    {
        return match($type) {
            'new_request' => 'fa-file-circle-plus', // Perfect icon for new requests
           
            default => 'fa-bell'
        };
    }
}