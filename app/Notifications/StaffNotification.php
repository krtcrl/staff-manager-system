<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class StaffNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->data['title'],
            'message' => $this->data['message'],
            'url' => $this->data['url'],
            'type' => $this->data['type'] ?? 'general',
            'timestamp' => now()->toDateTimeString(),
            'icon' => $this->getIconForType($this->data['type'] ?? 'general')
        ];
    }

    protected function getIconForType(string $type): string
    {
        return match($type) {
            'approval' => 'fa-thumbs-up',       // Approval icon
            'progress' => 'fa-arrow-right',     // Progress update icon
            'final_approval' => 'fa-file-signature', // Moving to final approval
            'completion' => 'fa-check-circle',  // Completion icon
            default => 'fa-bell'                // Default icon
        };
    }
}   