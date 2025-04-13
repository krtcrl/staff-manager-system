<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

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
    
        // Final approval email
        if ($this->data['type'] == 'final_approval') {
            return (new MailMessage)
                ->subject('Final Request Approval')
                ->line("Your request {$this->data['request_id']} has been approved by Final Manager {$this->data['manager_number']}")
                ->action('View Request', url($this->data['url']))
                ->line('Thank you for using our application!');
        }
    
        // Regular approval email
        if ($this->data['type'] == 'approval') {
            return (new MailMessage)
                ->subject('Your Request Approval Status')
                ->line("Your request {$this->data['request_id']} has been approved by Manager {$this->data['manager_number']}")
                ->action('View Request', url($this->data['url']))
                ->line('Thank you for using our application!');
        }
    
        // Completed request (moved to history)
        if ($this->data['type'] == 'completed') {
            return (new MailMessage)
                ->subject('Your Request Has Been Completed')
                ->line("Your request has completed the approval process and has been moved to request history.")
                ->action('View Request History', url($this->data['url']))
                ->line('Thank you for using our application!');
        }

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
