<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

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
        return ['database'];
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
