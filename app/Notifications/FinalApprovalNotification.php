<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class FinalApprovalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $finalRequest;
    public $url;
    public $managerNumber;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\FinalRequest  $finalRequest
     * @param  string  $url
     * @param  int  $managerNumber
     * @return void
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
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database']; // We are using database notifications only for now
    }

    /**
     * Build the database notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Final Approval Completed',
            'request_id' => $this->finalRequest->unique_code,
            'message' => "Your final approval request {$this->finalRequest->unique_code} has been approved by Manager {$this->managerNumber}.",
            'url' => url("/staff/final/{$this->finalRequest->unique_code}"), // Corrected URL to match your route
            'type' => 'final_approval_completed', // Specific type for final approval
            'timestamp' => now()->toDateTimeString(),
            'icon' => 'fa-thumbs-up' // Changed to thumbs-up icon
        ];
    }

    /**
     * Get the appropriate icon for the notification type.
     *
     * @param  string  $type
     * @return string
     */
    protected function getIconForType(string $type): string
    {
        return match ($type) {
            'final_approval_completed' => 'fa-thumbs-up', // Changed to thumbs-up icon
            default => 'fa-bell', // Default icon
        };
    }
}
