<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UpdatedNotification extends Notification implements ShouldQueue
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

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Updated Request Notification')
            ->greeting("Hello Manager {$this->managerNumber},")
            ->line("The request with code **{$this->request->unique_code}** that you previously rejected has been updated by the staff.")
            ->line('Please review the updated request at your earliest convenience.')
            ->action('View Updated Request', $this->url)
            ->line('Thank you.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Request Updated',
            'request_id' => $this->request->id,
            'message' => "The request {$this->request->unique_code} you rejected has been updated. Manager {$this->managerNumber}, please review the updated details.",
            'url' => $this->url,
            'type' => 'updated',
            'timestamp' => now()->toDateTimeString(),
            'icon' => 'fa-sync-alt'
        ];
    }

    protected function getIconForType(string $type): string
    {
        return match($type) {
            'updated' => 'fa-sync-alt',
            default => 'fa-bell'
        };
    }
}
