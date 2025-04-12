<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

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

        return (new MailMessage)
            ->from('joshcarillo022@gmail.com', 'ST Approval System')
            ->replyTo('no-reply@example.com', 'No Reply')
            ->subject('ACTION REQUIRED: New Request - ' . $this->request->part_number)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new request requires your immediate attention:')
            ->line('Part Number: ' . $this->request->part_number)
            ->line('Part Name: ' . $this->request->part_name)
            ->line('Submitted by: ' . ($this->staff?->name ?? 'System'))
            ->action('Review & Approve', $this->url)
            ->line('This is an automated notification - please do not reply.')
            ->salutation('Regards, ST Approval System');
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
