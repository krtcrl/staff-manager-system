<?php

namespace App\Notifications;

use App\Mail\StaffNotificationMail;
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

    // Add 'mail' channel to send an email as well as save to the database
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    // Define the email format
    public function toMail($notifiable)
    {
        \Log::info('Sending staff email to: ' . $notifiable->email);
    
        // Use the custom Mailable for staff notifications
        return (new StaffNotificationMail($this->data))
                    ->to($notifiable->email);
    }

    // Define how the database notification should be stored
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

    // Determine the icon based on the notification type
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
