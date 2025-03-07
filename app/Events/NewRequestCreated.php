<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use App\Models\RequestModel;

class NewRequestCreated implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $request;

    public function __construct(RequestModel $request)
    {
        $this->request = $request;
    }

    public function broadcastOn()
    {
        return new Channel('requests-channel');
    }

    public function broadcastAs()
    {
        return 'new-request';
    }
}
