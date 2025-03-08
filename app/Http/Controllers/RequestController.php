<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestModel;
use App\Models\Manager;
use Illuminate\Support\Facades\Auth;
use App\Events\NewRequestCreated;
use Illuminate\Support\Facades\Log;

class RequestController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Debugging: Check if the file is being received
            if ($request->hasFile('attachment')) {
                Log::info('File received:', [
                    'name' => $request->file('attachment')->getClientOriginalName(),
                    'size' => $request->file('attachment')->getSize(),
                ]);
            } else {
                Log::info('No file received.');
            }

            // Validate the request data
            $validatedData = $request->validate([
                'unique_code' => 'required|string|max:255',
                'part_number' => 'required|string|max:255',
                'part_name' => 'required|string|max:255',
                'process_type' => 'required|string|max:255',
                'uph' => 'required|integer',
                'description' => 'nullable|string',
                'status' => 'required|string|max:255',
                'attachment' => 'nullable|file|mimes:pdf|max:2048', // Validate PDF file (max 2MB)
            ]);

            // Handle file upload
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('attachments', 'public'); // Store the file in the "attachments" directory
                $validatedData['attachment'] = $attachmentPath; // Save the file path in the database
            } else {
                $validatedData['attachment'] = null; // Ensure attachment is null if no file is uploaded
            }

            // Debugging: Log the validated data
            Log::info('Validated Data:', $validatedData);

            // Insert the data into the database
            $requestModel = RequestModel::create($validatedData);

            if ($requestModel) {
                // Broadcast the event to notify all users in real-time
                broadcast(new NewRequestCreated($requestModel))->toOthers();

                return response()->json(['success' => 'Request submitted successfully!', 'request' => $requestModel]);
            } else {
                return response()->json(['error' => 'Failed to submit request.'], 500);
            }
        } catch (\Exception $e) {
            // Log the error
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
}