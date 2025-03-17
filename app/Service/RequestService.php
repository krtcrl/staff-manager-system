<?php

namespace App\Services;

use App\Models\RequestModel;
use App\Models\FinalRequest;

class RequestService
{
    /**
     * Move a completed request to the finalrequests table.
     *
     * @param int $requestId
     * @return bool
     */
    public function moveCompletedRequestToFinal($requestId)
    {
        // Find the request
        $request = RequestModel::findOrFail($requestId);

        // Check if the request is completed
        if ($this->isRequestCompleted($request)) {
            // Move the request to the finalrequests table
            FinalRequest::create([
                'unique_code' => $request->unique_code,
                'part_number' => $request->part_number,
                'description' => $request->description,
                'process_type' => $request->process_type,
                'current_process_index' => $request->current_process_index,
                'total_processes' => $request->total_processes,
                'manager_1_status' => $request->manager_1_status,
                'manager_2_status' => $request->manager_2_status,
                'manager_3_status' => $request->manager_3_status,
                'manager_4_status' => $request->manager_4_status,
            ]);

            // Delete the request from the requests table
            $request->delete();

            return true;
        }

        return false;
    }

    /**
     * Check if a request is completed.
     *
     * @param RequestModel $request
     * @return bool
     */
    protected function isRequestCompleted(RequestModel $request)
    {
        // Check if all managers have approved
        $managersApproved = $request->manager_1_status === 'approved' &&
                            $request->manager_2_status === 'approved' &&
                            $request->manager_3_status === 'approved' &&
                            $request->manager_4_status === 'approved';

        // Check if all processes are completed
        $processesCompleted = $request->current_process_index >= $request->total_processes;

        return $managersApproved && $processesCompleted;
    }
}