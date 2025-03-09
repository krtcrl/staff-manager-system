<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

use App\Models\RequestModel;
use Illuminate\Support\Facades\Log;

class NewRequestCreated implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $newRequestsToday;
    

    public function __construct()
    {
        $this->newRequestsToday = RequestModel::whereDate('created_at', today())->count();
        Log::info('NewRequestCreated event fired. Count:', ['count' => $this->newRequestsToday]);
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
