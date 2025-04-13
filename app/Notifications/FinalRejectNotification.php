<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class FinalRejectNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $finalRequest;
    public $url;
    public $managerNumber;
    public $rejectionReason;

    // Constructor to accept necessary data
    public function __construct($finalRequest, $url, $managerNumber, $rejectionReason)
    {
        $this->finalRequest = $finalRequest;
        $this->url = $url;
        $this->managerNumber = $managerNumber;
        $this->rejectionReason = $rejectionReason;
    }

    // Specify the delivery channels (database and mail)
    public function via($notifiable)
    {
        return ['database', 'mail']; // Send to both database and email
    }

    // Define the structure of the database notification
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Final Request Rejected', // Notification title
            'request_id' => $this->finalRequest->id,
            'message' => "Your request {$this->finalRequest->unique_code} has been rejected by Final Manager {$this->managerNumber}. Reason: {$this->rejectionReason}",
            'url' => $this->url,
            'type' => 'rejected', // Mark as 'rejected'
            'timestamp' => now()->toDateTimeString(),
            'icon' => $this->getIconForType('rejected') // Set the icon for rejection
        ];
    }

    // Define the structure of the email notification
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Final Request Rejected')
            ->line("Your final request {$this->finalRequest->unique_code} has been rejected by Final Manager {$this->managerNumber}.")
            ->line("Reason: {$this->rejectionReason}")
            ->action('View Request', url($this->url)) // Link to request details
            ->line('Thank you for using our application!');
    }

    // This method selects the appropriate icon based on the notification type
    protected function getIconForType(string $type): string
    {
        return match($type) {
            'rejected' => 'fa-times-circle', // Font Awesome icon for rejection (red X)
            default => 'fa-bell', // Default icon for other notification types
        };
    }
}
