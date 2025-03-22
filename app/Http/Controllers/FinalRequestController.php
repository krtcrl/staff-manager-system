<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinalRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class FinalManagerController extends Controller
{
    /**
     * Display the Final Request List.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $finalRequests = FinalRequest::orderBy('created_at', 'desc')->paginate(10);
        return view('manager.finalrequest_list', compact('finalRequests'));
    }

    /**
     * Display the details of a specific final request.
     *
     * @param string $unique_code
     * @return \Illuminate\View\View
     */
    public function finalRequestDetails($unique_code)
    {
        $finalRequest = FinalRequest::where('unique_code', $unique_code)->first();

        if (!$finalRequest) {
            abort(404);
        }

        return view('manager.finalrequest_details', compact('finalRequest'));
    }
    

    /**
     * Approve a final request by the current manager.
     *
     * @param Request $request
     * @param string $unique_code
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveFinalRequest(Request $request, $unique_code)
    {
        try {
            $manager = Auth::guard('manager')->user();
            $managerNumber = $manager->manager_number;

            $finalRequest = FinalRequest::where('unique_code', $unique_code)->firstOrFail();

            $managerToStatusMapping = [
                1 => 'manager_1_status',
                5 => 'manager_2_status',
                6 => 'manager_3_status',
                7 => 'manager_4_status',
                8 => 'manager_5_status',
                9 => 'manager_6_status',
            ];

            if (!array_key_exists($managerNumber, $managerToStatusMapping)) {
                return redirect()->back()->with('error', 'You are not authorized to approve this request.');
            }

            $statusColumn = $managerToStatusMapping[$managerNumber];

            // Ensure previous managers have approved
            $allPreviousApproved = true;
            foreach ($managerToStatusMapping as $mgrNumber => $column) {
                if ($mgrNumber === $managerNumber) {
                    break;
                }
                if ($finalRequest->$column !== 'approved') {
                    $allPreviousApproved = false;
                    break;
                }
            }

            if (!$allPreviousApproved) {
                return redirect()->back()->with('error', 'Previous managers must approve first.');
            }

            $finalRequest->$statusColumn = 'approved';
            $finalRequest->save();

            // Broadcast status update
            $this->broadcastStatusUpdate($finalRequest);

            // Check if all managers have approved
            $allApproved = true;
            foreach ($managerToStatusMapping as $column) {
                if ($finalRequest->$column !== 'approved') {
                    $allApproved = false;
                    break;
                }
            }

            if ($allApproved) {
                $finalRequest->status = 'completed';
                $finalRequest->save();
            }

            return redirect()->route('manager.finalrequest.details', ['unique_code' => $finalRequest->unique_code])
                             ->with('success', 'Request approved successfully!');
        } catch (\Exception $e) {
            Log::error('Error in approval process:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'An error occurred while approving.');
        }
    }

    /**
     * Reject a final request by the current manager.
     *
     * @param Request $request
     * @param string $unique_code
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectFinalRequest(Request $request, $unique_code)
    {
        try {
            $manager = Auth::guard('manager')->user();
            $managerNumber = $manager->manager_number;

            $finalRequest = FinalRequest::where('unique_code', $unique_code)->firstOrFail();

            $managerToStatusMapping = [
                1 => 'manager_1_status',
                5 => 'manager_2_status',
                6 => 'manager_3_status',
                7 => 'manager_4_status',
                8 => 'manager_5_status',
                9 => 'manager_6_status',
            ];

            if (!array_key_exists($managerNumber, $managerToStatusMapping)) {
                return redirect()->back()->with('error', 'You are not authorized to reject this request.');
            }

            $statusColumn = $managerToStatusMapping[$managerNumber];

            $finalRequest->$statusColumn = 'rejected';
            $finalRequest->rejection_reason = $request->input('rejection_reason');
            $finalRequest->save();

            // Broadcast status update
            $this->broadcastStatusUpdate($finalRequest);

            return redirect()->route('manager.finalrequest.details', ['unique_code' => $finalRequest->unique_code])
                             ->with('success', 'Request rejected successfully!');
        } catch (\Exception $e) {
            Log::error('Error in rejection process:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'An error occurred while rejecting.');
        }
    }

    /**
     * Broadcast the status update using Pusher.
     *
     * @param FinalRequest $finalRequest
     */
    private function broadcastStatusUpdate($finalRequest)
    {
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
            ]
        );

        $pusher->trigger('finalrequests-channel', 'status-updated', [
            'finalRequest' => $finalRequest,
        ]);
    }
}
