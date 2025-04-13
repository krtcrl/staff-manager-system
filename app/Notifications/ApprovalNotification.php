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

    public function toMail($notifiable)
    {
        $isFinal = str_contains(strtolower($this->managerNumber), 'final');
    
        $mail = new MailMessage;
    
        if ($isFinal) {
            $mail->subject('Final Approval Required for Request')
                 ->line("Part number {$this->request->part_number} is ready for **final approval**.")
                 ->line("You are one of the final approvers. Please review the request.")
                 ->action('Review Final Approval Request', $this->url)
                 ->line('This request has passed all previous approvals and is now awaiting final sign-off.');
        } else {
            $mail->subject('Request Awaiting Your Approval')
                 ->line("Part number {$this->request->part_number} is awaiting your approval.")
                 ->line("Manager {$this->managerNumber}, please review the request.")
                 ->action('Review Request', $this->url)
                 ->line('Please review and approve or reject the request as necessary.');
        }
    
        return $mail;
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
