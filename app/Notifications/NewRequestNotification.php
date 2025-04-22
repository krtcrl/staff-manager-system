<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

use App\Mail\NewRequestMail;
use Illuminate\Support\Facades\Mail;

class NewRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $request;
    public $url;
    public $staff;

    public function __construct($request, $url, $staff = null)
    {
        $this->request = $request;
        $this->url = $url;
        $this->staff = $staff;
    }

    public function via($notifiable)
    {
        // Enable both mail and database notifications
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        Log::info('Attempting to send email to: ' . $notifiable->email);
    
        return (new NewRequestMail($this->request, $this->url, $this->staff, $notifiable))
            ->to($notifiable->email);
    }

    public function toDatabase($notifiable)
    {
        Log::info('Storing new request notification for: ' . $notifiable->email);

        return [
            'title' => 'New Request Created',
            'message' => 'A new request has been submitted: ' . $this->request->part_number,
            'url' => $this->url,
            'type' => 'new_request',
            'timestamp' => now()->toDateTimeString(),
            'icon' => $this->getIconForType('new_request'),
            'request_id' => $this->request->id
        ];
    }

    protected function getIconForType(string $type): string
    {
        return match ($type) {
            'new_request' => 'fa-file-circle-plus',
            default => 'fa-bell'
        };
    }
}
