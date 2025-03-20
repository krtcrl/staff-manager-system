<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\RequestModel;
use App\Models\Manager;
use Illuminate\Support\Facades\Auth;
use App\Events\NewRequestCreated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\PartProcess;
use App\Models\Process;
use App\Services\RequestService; // Import the service

class RequestController extends Controller
{
    protected $requestService;

    public function __construct(RequestService $requestService)
    {
        $this->requestService = $requestService;
    }

    public function store(Request $request)
    {
        try {
            // ✅ Validate the request data
            $validatedData = $request->validate([
                'unique_code' => 'required|string|max:255',
                'part_number' => 'required|string|max:255',
                'part_name' => 'required|string|max:255',
                'uph' => 'required|integer',
                'description' => 'nullable|string',
                'revision_type' => 'required|string|max:1',
                'standard_yield_percentage' => 'nullable|numeric',
                'standard_yield_dollar_per_hour' => 'nullable|numeric',
                'actual_yield_percentage' => 'nullable|numeric',
                'actual_yield_dollar_per_hour' => 'nullable|numeric',
                'bottle_neck_uph' => 'nullable|integer',  // ➕ Added Bottle Neck UPH validation
                'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
                'final_approval_attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048', // ➕ Final Approval Attachment validation
            ]);

            return DB::transaction(function () use ($validatedData, $request) {
                
                // Fetch processes for the selected part number
                $processes = DB::table('part_processes')
                    ->where('part_number', $validatedData['part_number'])
                    ->orderBy('process_order')
                    ->get();

                if ($processes->isEmpty()) {
                    return response()->json(['error' => 'No processes found for the selected part number.'], 400);
                }

                // Count total processes based on process_order
                $totalProcesses = DB::table('part_processes')
                    ->where('part_number', $validatedData['part_number'])
                    ->count();

                // Set process-related fields
                $validatedData['process_type'] = $processes->first()->process_type; 
                $validatedData['current_process_index'] = 1; 
                $validatedData['total_processes'] = $totalProcesses; 

                // ✅ Handle file uploads
                if ($request->hasFile('attachment')) {
                    $attachmentPath = $request->file('attachment')->store('attachments', 'public');
                    $validatedData['attachment'] = $attachmentPath;
                } else {
                    $validatedData['attachment'] = null;
                }

                // ✅ Handle final approval attachment upload
                if ($request->hasFile('final_approval_attachment')) {
                    $finalApprovalPath = $request->file('final_approval_attachment')->store('final_approval_attachments', 'public');
                    $validatedData['final_approval_attachment'] = $finalApprovalPath;
                } else {
                    $validatedData['final_approval_attachment'] = null;
                }

                // ✅ Insert into database
                $requestModel = RequestModel::create([
                    'unique_code' => $validatedData['unique_code'],
                    'part_number' => $validatedData['part_number'],
                    'part_name' => $validatedData['part_name'],
                    'uph' => $validatedData['uph'],
                    'bottle_neck_uph' => $validatedData['bottle_neck_uph'],   // ➕ Added Bottle Neck UPH
                    'description' => $validatedData['description'] ?? null,
                    'revision_type' => $validatedData['revision_type'],
                    'standard_yield_percentage' => $validatedData['standard_yield_percentage'],
                    'standard_yield_dollar_per_hour' => $validatedData['standard_yield_dollar_per_hour'],
                    'actual_yield_percentage' => $validatedData['actual_yield_percentage'],
                    'actual_yield_dollar_per_hour' => $validatedData['actual_yield_dollar_per_hour'],
                    'attachment' => $validatedData['attachment'] ?? null,
                    'final_approval_attachment' => $validatedData['final_approval_attachment'] ?? null, // ✅ Store final approval attachment
                    'process_type' => $validatedData['process_type'],
                    'current_process_index' => $validatedData['current_process_index'],
                    'total_processes' => $validatedData['total_processes'],
                ]);

                if ($requestModel) {
                    // ✅ Broadcast event after transaction commits
                    DB::afterCommit(function () use ($requestModel) {
                        broadcast(new NewRequestCreated($requestModel))->toOthers();
                    });

                    return response()->json(['success' => 'Request submitted successfully!', 'request' => $requestModel]);
                } else {
                    return response()->json(['error' => 'Failed to submit request.'], 500);
                }
            });

        } catch (\Exception $e) {
            Log::error('Error in store method:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'An error occurred while submitting the request.'], 500);
        }
    }
    

    public function destroy($id)
    {
        try {
            // Find the request in the database
            $requestModel = RequestModel::find($id);

            if (!$requestModel) {
                return redirect()->route('staff.dashboard')->with('error', 'Request not found.');
            }

            // Delete the request
            $requestModel->delete();

            return redirect()->route('staff.dashboard')->with('success', 'Request deleted successfully.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in destroy method:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('staff.dashboard')->with('error', 'Failed to delete request.');
        }
    }

    public function update(Request $request, $id)
{
    Log::info('Update method called for request ID: ' . $id);

    try {
        // ✅ Validate the request data
        $validatedData = $request->validate([
            'description' => 'nullable|string|max:255',
            'revision_type' => 'required|string|max:255',
            'part_number' => 'required|string|max:255',
            'part_name' => 'required|string|max:255',
            'uph' => 'required|integer',
            'bottle_neck_uph' => 'nullable|integer',    
            'standard_yield_percentage' => 'nullable|numeric',
            'standard_yield_dollar_per_hour' => 'nullable|numeric',
            'actual_yield_percentage' => 'nullable|numeric',
            'actual_yield_dollar_per_hour' => 'nullable|numeric',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'final_approval_attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        Log::info('Validated Data:', $validatedData);

        // ✅ Find the request
        $requestModel = RequestModel::findOrFail($id);
        Log::info('Request found:', ['request' => $requestModel]);

        // ✅ Clear description if request was rejected
        if ($requestModel->status === 'rejected') {
            $validatedData['description'] = null;
            Log::info('Description cleared due to rejection');
        }

        // ✅ Handle attachment removal
        if ($request->has('remove_attachment')) {
            Log::info('Removing main attachment');

            if ($requestModel->attachment && Storage::disk('public')->exists($requestModel->attachment)) {
                Storage::disk('public')->delete($requestModel->attachment);
            }
            $validatedData['attachment'] = null;
        }

        // ✅ Handle final approval attachment removal
        if ($request->has('remove_final_approval_attachment')) {
            Log::info('Removing final approval attachment');

            if ($requestModel->final_approval_attachment && Storage::disk('public')->exists($requestModel->final_approval_attachment)) {
                Storage::disk('public')->delete($requestModel->final_approval_attachment);
            }
            $validatedData['final_approval_attachment'] = null;
        }

        // ✅ Handle new main attachment upload
        if ($request->hasFile('attachment')) {
            Log::info('New main attachment uploaded');

            // Delete old attachment if it exists
            if ($requestModel->attachment && Storage::disk('public')->exists($requestModel->attachment)) {
                Storage::disk('public')->delete($requestModel->attachment);
            }

            // Store new attachment
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
            Log::info('Main attachment stored at:', ['path' => $attachmentPath]);
            $validatedData['attachment'] = $attachmentPath;
        }

        // ✅ Handle new final approval attachment upload
        if ($request->hasFile('final_approval_attachment')) {
            Log::info('New final approval attachment uploaded');

            // Delete old final approval attachment if it exists
            if ($requestModel->final_approval_attachment && Storage::disk('public')->exists($requestModel->final_approval_attachment)) {
                Storage::disk('public')->delete($requestModel->final_approval_attachment);
            }

            // Store new final approval attachment
            $finalApprovalPath = $request->file('final_approval_attachment')->store('final_approval_attachments', 'public');
            Log::info('Final approval attachment stored at:', ['path' => $finalApprovalPath]);
            $validatedData['final_approval_attachment'] = $finalApprovalPath;
        }

        // ✅ Update the request with the new data
        $requestModel->update($validatedData);

        // Move request to final requests if completed
        $this->requestService->moveCompletedRequestToFinal($requestModel->id);

        Log::info('Request updated successfully');

        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        Log::error('Error updating request:', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

}