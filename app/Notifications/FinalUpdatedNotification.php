<?php

namespace App\Notifications;

use App\Mail\FinalUpdatedRequestMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class FinalUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $finalRequest;
    public $url;
    public $managerNumber;

    public function __construct($finalRequest, $url, $managerNumber)
    {
        $this->finalRequest = $finalRequest;
        $this->url = $url;
        $this->managerNumber = $managerNumber;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];  // Added mail channel
    }

    public function toMail($notifiable)
    {
        // Using the custom Mailable for the updated final request
        return (new FinalUpdatedRequestMail($this->finalRequest, $this->url, $this->managerNumber, $notifiable))
                    ->to($notifiable->email);
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Final Request Updated',
            'final_request_id' => $this->finalRequest->id,
            'message' => "The final request {$this->finalRequest->unique_code} you rejected has been updated. Manager {$this->managerNumber}, please review the updated details.",
            'url' => $this->url,
            'type' => 'updated',
            'timestamp' => now()->toDateTimeString(),
            'icon' => 'fa-sync-alt',
        ];
    }

    protected function getIconForType(string $type): string
    {
        return match($type) {
            'updated' => 'fa-sync-alt',
            default => 'fa-bell',
        };
    }
}
