<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApprovalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $request;
    public $url;
    public $managerNumber;

    public function __construct($request, $url, $managerNumber)
    {
        $this->request = $request;
        $this->url = $url;
        $this->managerNumber = $managerNumber;
    }

    // Add 'mail' to the channels to send email notifications as well
    public function via($notifiable)
    {
        return ['mail', 'database'];  // Send email and save to database
    }

    // Define how the email should be sent
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Request Awaiting Your Approval')
                    ->line("Part number {$this->request->part_number} is awaiting your approval.")
                    ->line("Manager {$this->managerNumber}, please review the request.")
                    ->action('Review Request', $this->url)
                    ->line('Please review and approve or reject the request as necessary.');
    }

    // Define how the database notification should be stored
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Approval Required',
            'request_id' => $this->request->id,
            'message' => "Part number {$this->request->part_number} is awaiting your approval. Manager {$this->managerNumber}, please review the request.",
            'url' => $this->url,
            'type' => 'approval_required',
            'timestamp' => now()->toDateTimeString(),
            'icon' => 'fa-user-check' // Font Awesome icon for approval
        ];
    }

    protected function getIconForType(string $type): string
    {
        return match($type) {
            'approval_required' => 'fa-user-check',
            default => 'fa-bell'
        };
    }
}
