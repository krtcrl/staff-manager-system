<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestModel;
use App\Models\Manager;
use Illuminate\Support\Facades\Auth;
use App\Events\NewRequestCreated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\PartProcess;
use App\Models\Process;


class RequestController extends Controller
{
    public function store(Request $request)
{
    try {
        // Validate the request data
        $validatedData = $request->validate([
            'unique_code' => 'required|string|max:255',
            'part_number' => 'required|string|max:255',
            'part_name' => 'required|string|max:255',
            'uph' => 'required|integer',
            'description' => 'nullable|string',
            'revision_type' => 'required|string|max:1',
            'attachment' => 'nullable|file|mimes:pdf|max:2048',
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

            // ✅ Count total processes based on process_order for the given part_number
            $totalProcesses = DB::table('part_processes')
                ->where('part_number', $validatedData['part_number'])
                ->count();

            // Set process-related fields
            $validatedData['process_type'] = $processes->first()->process_type; // First process type
            $validatedData['current_process_index'] = 1; // Start at first process
            $validatedData['total_processes'] = $totalProcesses; // Correct count of processes

            // Handle file upload
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('attachments', 'public');
                $validatedData['attachment'] = $attachmentPath;
            } else {
                $validatedData['attachment'] = null;
            }

            // Insert into database
            $requestModel = RequestModel::create($validatedData);

            if ($requestModel) {
                broadcast(new NewRequestCreated($requestModel))->toOthers();
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
}