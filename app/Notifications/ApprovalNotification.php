<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

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

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Approval Required',
            'request_id' => $this->request->id,
            'message' => "Part number {$this->request->part_number} is awaiting your approval. Manager {$this->managerNumber}, please review the request.",
            'url' => $this->url,
            'type' => 'approval_required', // This must match exactly
            'timestamp' => now()->toDateTimeString(),
            'icon' => 'fa-user-check' // Hardcoded to ensure it's always used
        ];
    }

    protected function getIconForType(string $type): string
    {
        return match($type) {
            'approval_required' => 'fa-user-check', // Font Awesome icon for approval
            default => 'fa-bell'
        };
    }
}