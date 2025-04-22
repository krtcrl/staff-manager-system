<?php

namespace App\Notifications;

use App\Mail\FinalApprovalRequestMail;
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
        return ['mail', 'database'];  // Send email and save to database
    }

    /**
     * Send the email using the custom Mailable.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Using the custom Mailable for final approval
        return (new FinalApprovalRequestMail($this->finalRequest, $this->url, $this->managerNumber, $notifiable))
                    ->to($notifiable->email);
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
            'url' => url("/staff/final/{$this->finalRequest->unique_code}"),
            'type' => 'final_approval_completed',
            'timestamp' => now()->toDateTimeString(),
            'icon' => 'fa-thumbs-up'
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
        return match($type) {
            'final_approval_completed' => 'fa-thumbs-up',
            default => 'fa-bell',
        };
    }
}
