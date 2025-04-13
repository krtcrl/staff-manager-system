<?php

namespace App\Notifications;

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

    /**
     * Create a new notification instance.
     *
     * @param $finalRequest
     * @param string $url
     * @param int $managerNumber
     */
    public function __construct($finalRequest, $url, $managerNumber)
    {
        $this->finalRequest = $finalRequest;
        $this->url = $url;
        $this->managerNumber = $managerNumber;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];  // Added mail channel
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Final Request Updated')
            ->greeting("Hello Manager {$this->managerNumber},")
            ->line("The final request with code **{$this->finalRequest->unique_code}** that you previously rejected has been updated by the staff.")
            ->line('Please review the updated final request at your earliest convenience.')
            ->action('View Updated Final Request', $this->url)
            ->line('Thank you.');
    }

    /**
     * Get the database representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
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

    /**
     * Get an icon for different notification types.
     *
     * @param string $type
     * @return string
     */
    protected function getIconForType(string $type): string
    {
        return match($type) {
            'updated' => 'fa-sync-alt',
            default => 'fa-bell',
        };
    }
}
