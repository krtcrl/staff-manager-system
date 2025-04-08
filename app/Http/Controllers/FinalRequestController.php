<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinalRequest;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class FinalRequestController extends Controller
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
    public function show($id)
    {
        // Retrieve the final request along with the associated staff
        $finalRequest = FinalRequest::with('staff')->findOrFail($id);
        
        return view('manager.finalrequest_detail', compact('finalRequest'));
    }

    /**
     * Approve or update a final request by the current manager.
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // ✅ Validate incoming data
            $validatedData = $request->validate([
                'description' => 'nullable|string|max:255',
                'part_name'   => 'required|string|max:255',
                'attachment'  => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,jpg,png|max:2048',
            ]);

            // ✅ Find the final request by ID
            $finalRequest = FinalRequest::findOrFail($id);

            // ✅ Update the fields
            $finalRequest->description = $validatedData['description'];
            $finalRequest->part_name = $validatedData['part_name'];
            $finalRequest->is_edited = true;  // Mark as edited

            // ✅ Handle new attachment upload
            if ($request->hasFile('attachment')) {
                // Remove the old attachment if it exists
                if ($finalRequest->attachment && Storage::disk('public')->exists($finalRequest->attachment)) {
                    Storage::disk('public')->delete($finalRequest->attachment);
                }

                // Store the new attachment
                $path = $request->file('attachment')->store('attachments', 'public');
                $finalRequest->attachment = $path;
            }

            // ✅ Reset rejected managers' statuses to 'pending'
            $managerToStatusMapping = [
                1 => 'manager_1_status',
                5 => 'manager_2_status',
                6 => 'manager_3_status',
                7 => 'manager_4_status',
                8 => 'manager_5_status',
                9 => 'manager_6_status',
            ];

            foreach ($managerToStatusMapping as $managerNum => $statusColumn) {
                if ($finalRequest->$statusColumn === 'rejected') {
                    $finalRequest->$statusColumn = 'pending';
                }
            }

            // ✅ Save the changes
            $finalRequest->save();

            // ✅ Return a success message with JSON or redirect
            return response()->json([
                'success' => 'Final request updated successfully!',
                'finalRequest' => $finalRequest
            ]);

        } catch (\Exception $e) {
            // ✅ Handle any exceptions
            Log::error('Error updating final request:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
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
