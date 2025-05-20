<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinalRequest;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Manager; 
use Pusher\Pusher;
use App\Notifications\FinalUpdatedNotification;

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
            'attachment' => 'nullable|file|mimes:xlsx,xls,docx,pdf|max:2048',
        ]);

        // ✅ Retrieve the final request
        $finalRequest = FinalRequest::findOrFail($id);

        // ✅ Update description and mark as edited
        if (isset($validatedData['description'])) {
            $finalRequest->description = $validatedData['description'];
        }
        $finalRequest->is_edited = true;

        // ✅ Handle file upload if present
        if ($request->hasFile('attachment')) {
            $originalFileName = $request->file('attachment')->getClientOriginalName();
            $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
            $fileNameWithoutExt = pathinfo($originalFileName, PATHINFO_FILENAME);

            // Delete old file if it exists
            if ($finalRequest->attachment) {
                Storage::disk('public')->delete('attachments/' . $finalRequest->attachment);
                
                // Check if the new file has the same base name as the current one
                if (strpos($finalRequest->attachment, $finalRequest->unique_code . '_' . $fileNameWithoutExt) === 0) {
                    // This is a revision of the same file
                    // Extract revision number if exists
                    $pattern = '/^' . preg_quote($finalRequest->unique_code . '_' . $fileNameWithoutExt, '/') . '(?:\((\d+)\))?\.' . preg_quote($fileExtension, '/') . '$/';
                    preg_match($pattern, $finalRequest->attachment, $matches);
                    
                    $revisionNumber = isset($matches[1]) ? (int)$matches[1] + 1 : 2;
                    $newFileName = $finalRequest->unique_code . '_' . $fileNameWithoutExt . '(' . $revisionNumber . ').' . $fileExtension;
                } else {
                    // Completely different file
                    $newFileName = $finalRequest->unique_code . '_' . $fileNameWithoutExt . '.' . $fileExtension;
                }
            } else {
                // First time attachment
                $newFileName = $finalRequest->unique_code . '_' . $fileNameWithoutExt . '.' . $fileExtension;
            }

            // Store the new file
            $request->file('attachment')->storeAs('attachments', $newFileName, 'public');
            $finalRequest->attachment = $newFileName;
        }

        // ✅ Capture rejected final managers
        $rejectedManagers = [];
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
                $rejectedManagers[] = $managerNum;
                $finalRequest->$statusColumn = 'pending'; // Reset status to 'pending'
            }
        }

        // ✅ Save updates
        $finalRequest->save();

        // ✅ Notify rejected final managers using FinalUpdatedNotification
        foreach ($rejectedManagers as $managerNum) {
            $manager = Manager::find($managerNum);
            if ($manager) {
                // Send notification to each manager who previously rejected
                $manager->notify(new \App\Notifications\FinalUpdatedNotification(
                    $finalRequest,
                    route('manager.finalrequest.details', $finalRequest->unique_code),
                    $managerNum
                ));
            }
        }

        return response()->json([
            'success' => 'Final request updated successfully and managers notified!',
            'finalRequest' => $finalRequest,
        ]);
    } catch (\Exception $e) {
        // ✅ Log the exception message and stack trace
        Log::error('Error updating final request:', [
            'message' => $e->getMessage(), // The main error message
            'file' => $e->getFile(),       // The file in which the error occurred
            'line' => $e->getLine(),       // The line number where the error occurred
            'trace' => $e->getTraceAsString(), // Full stack trace
            'request_data' => $request->all(), // Request data
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
