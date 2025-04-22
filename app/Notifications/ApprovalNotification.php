<?php

namespace App\Notifications;

use App\Mail\ApprovalRequestMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

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

    // Send email and database notification
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    // Send using the custom mailable
    public function toMail($notifiable)
    {
        // Optional: You can send the custom mail manually (or let Laravel handle it)
        return (new \App\Mail\ApprovalRequestMail($this->request, $this->url, $this->managerNumber, $notifiable))
                    ->to($notifiable->email);
    }

    // Store in database
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Approval Required',
            'request_id' => $this->request->id,
            'message' => "Part number {$this->request->part_number} is awaiting your approval. Manager {$this->managerNumber}, please review the request.",
            'url' => $this->url,
            'type' => 'approval_required',
            'timestamp' => now()->toDateTimeString(),
            'icon' => 'fa-user-check'
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
