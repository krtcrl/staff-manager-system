<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RejectNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $request;
    public $url;
    public $managerNumber;
    public $rejectionReason;

    public function __construct($request, $url, $managerNumber, $rejectionReason)
    {
        $this->request = $request;
        $this->url = $url;
        $this->managerNumber = $managerNumber;
        $this->rejectionReason = $rejectionReason;
    }

    // Add email and database channels
    public function via($notifiable)
    {
        return ['mail', 'database']; // Sends email and stores in the database
    }

    // Define email format
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Request Rejected by Manager')
            ->line("Your request {$this->request->unique_code} has been rejected by Manager {$this->managerNumber}.")
            ->line("Rejection Reason: {$this->rejectionReason}")
            ->action('View Request', url($this->url)) // Add the link to view the request
            ->line('Thank you for using our application!');
    }

    // Define database notification format
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Request Rejected',
            'request_id' => $this->request->id,
            'message' => "Your request {$this->request->unique_code} has been rejected by Manager {$this->managerNumber}. Reason: {$this->rejectionReason}",
            'url' => $this->url,
            'type' => 'rejected',
            'timestamp' => now()->toDateTimeString(),
            'icon' => $this->getIconForType('rejected'),
        ];
    }

    // Dynamically choose the icon based on notification type
    protected function getIconForType(string $type): string
    {
        return match($type) {
            'rejected' => 'fa-times-circle', // Red X icon for rejection
            default => 'fa-bell', // Default bell icon for other types
        };
    }
}
