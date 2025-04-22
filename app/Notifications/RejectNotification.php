<?php

namespace App\Notifications;

use App\Mail\RejectRequestMail; // Use the Mailable class for rejection mail
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

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

    // Use the RejectRequestMail Mailable for email format
    public function toMail($notifiable)
    {
        // Return the RejectRequestMail Mailable to handle the email
        return (new RejectRequestMail(
            $this->request, 
            $this->url, 
            $this->managerNumber, 
            $this->rejectionReason,
            $notifiable
        ))->to($notifiable->email); // Set the recipient email
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
