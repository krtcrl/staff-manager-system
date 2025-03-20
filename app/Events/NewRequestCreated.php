<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\RequestModel;

class NewRequestCreated implements ShouldBroadcast
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

    public function broadcastWith()
    {
        return [
            'request' => [
                'unique_code' => $this->request->unique_code,
                'part_number' => $this->request->part_number,
                'part_name' => $this->request->part_name,
                'description' => $this->request->description,
                'revision_type' => $this->request->revision_type,
                'uph' => $this->request->uph,
                'standard_yield_percentage' => $this->request->standard_yield_percentage,
                'standard_yield_dollar_per_hour' => $this->request->standard_yield_dollar_per_hour,
                'actual_yield_percentage' => $this->request->actual_yield_percentage,
                'actual_yield_dollar_per_hour' => $this->request->actual_yield_dollar_per_hour,
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