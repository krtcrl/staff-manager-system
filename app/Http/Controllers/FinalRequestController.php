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
                'final_approval_attachment' => 'nullable|file|mimes:xlsx,xls,docx,pdf|max:2048',
            ]);
    
            // ✅ Retrieve the final request
            $finalRequest = FinalRequest::findOrFail($id);
    
            // ✅ Update description and mark as edited
            if (isset($validatedData['description'])) {
                $finalRequest->description = $validatedData['description'];
            }
            $finalRequest->is_edited = true;
    
            // ✅ Handle file upload if present
            if ($request->hasFile('final_approval_attachment')) {
                // Delete old file if it exists
                if ($finalRequest->final_approval_attachment && Storage::disk('public')->exists($finalRequest->final_approval_attachment)) {
                    Storage::disk('public')->delete($finalRequest->final_approval_attachment);
                }
    
                // Store new file with the original name
                $originalFileName = $request->file('final_approval_attachment')->getClientOriginalName();
                $request->file('final_approval_attachment')->storeAs('final_approval_attachments', $originalFileName, 'public');
                $finalRequest->final_approval_attachment = $originalFileName;
            }
    
            // ✅ Manager status reset logic
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
    
            // ✅ Save updates
            $finalRequest->save();
    
            return response()->json([
                'success' => 'Final request updated successfully!',
                'finalRequest' => $finalRequest,
            ]);
        } catch (\Exception $e) {
            // ✅ Log and respond to exceptions
            Log::error('Error updating final request:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            return response()->json([
                'error' => 'An error occurred: ' . $e->getMessage()
            ], 500);
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
