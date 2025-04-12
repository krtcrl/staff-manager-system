<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class RejectNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $request;
    public $url;
    public $managerNumber;
    public $rejectionReason;

    public function __construct($request, $url, $managerNumber, $rejectionReason)
    {
        $this->request = $request;
        $this->url = $url;
        $this->managerNumber = $managerNumber;
        $this->rejectionReason = $rejectionReason;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Request Rejected',
            'request_id' => $this->request->id,
            'message' => "Your request {$this->request->unique_code} has been rejected by Manager {$this->managerNumber}. Reason: {$this->rejectionReason}",
            'url' => $this->url,
            'type' => 'rejected', // Make sure it's correctly marked as 'rejected'
            'timestamp' => now()->toDateTimeString(),
            'icon' => $this->getIconForType('rejected') // Dynamically choosing the icon for rejection
        ];
    }

    protected function getIconForType(string $type): string
    {
        return match($type) {
            'rejected' => 'fa-times-circle', // Font Awesome icon for rejection (red X)
            default => 'fa-bell', // Default bell icon for other types
        };
    }
}
