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

    public $request;

    public function __construct(RequestModel $request)
    {
        $this->request = $request;

        // Debugging log
        Log::info('NewRequestCreated event fired.', [
            'request_id' => $request->id,
            'part_number' => $request->part_number,
            'created_at' => $request->created_at
        ]);
    }

    public function broadcastOn()
    {
        return new Channel('requests-channel');
    }

    public function broadcastAs()
    {
        return 'new-request';
    }

    public function broadcastWith()
{
    Log::info('Broadcasting New Request:', ['request' => $this->request->toArray()]);

    return [
        'request' => [
            'unique_code' => $this->request->unique_code,
            'part_number' => $this->request->part_number,
            'description' => $this->request->description,
            'process_type' => $this->request->process_type,
            'current_process_index' => $this->request->current_process_index,
            'total_processes' => $this->request->total_processes,
            'manager_1_status' => $this->request->manager_1_status,
            'manager_2_status' => $this->request->manager_2_status,
            'manager_3_status' => $this->request->manager_3_status,
            'manager_4_status' => $this->request->manager_4_status,
            'created_at' => $this->request->created_at->toISOString(),
        ]
    ];
}

}
