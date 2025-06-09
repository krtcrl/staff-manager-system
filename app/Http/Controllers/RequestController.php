<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\RequestModel;
use App\Models\Manager;
use App\Models\Part;
use App\Models\PartProcess;
use App\Models\Process;
use Illuminate\Support\Facades\Auth;
use App\Events\NewRequestCreated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\RequestService;
use App\Notifications\NewRequestNotification;
use Illuminate\Support\Facades\Notification;

class RequestController extends Controller
{
    protected $requestService;

    public function __construct(RequestService $requestService)
    {
        $this->requestService = $requestService;
    }

   public function store(Request $request)
{
    DB::beginTransaction();
    \Log::debug('Incoming request data:', $request->all());

    try {
        // ✅ Validate incoming request
        $validatedData = $request->validate([
            'unique_code' => 'required|string|max:255',
            'part_number' => 'required|string|max:255',
            'part_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'attachment' => 'required|file|mimes:xls,xlsx,xlsb|max:20480',
            'is_new_part' => 'sometimes|string|in:true,false,1,0',
            'processes' => 'required|array',
            'processes.*.process_type' => 'required|string|max:255',
            'processes.*.process_order' => 'required|integer|min:1'
        ]);

        // Convert is_new_part to boolean
        $isNewPart = filter_var($request->input('is_new_part'), FILTER_VALIDATE_BOOLEAN);

        // ✅ Check if this is a new part and needs to be created
        if ($isNewPart) {
            $processes = $request->input('processes', []);
            
            // Create the part
            $part = Part::create([
                'part_number' => $validatedData['part_number'],
                'part_name' => $validatedData['part_name'],
                'description' => $validatedData['description'] ?? null,
                'created_by' => Auth::guard('staff')->id()
            ]);

            if (!$part) {
                throw new \Exception('Failed to create part record');
            }

            // Create part processes (only for new parts)
            foreach ($request->input('processes') as $process) {
                PartProcess::create([
                    'part_number' => $validatedData['part_number'],
                    'process_type' => $process['process_type'],
                    'process_order' => $process['process_order']
                ]);
            }
        } else {
            // For existing parts, validate that the processes match the part's processes
            $existingProcesses = PartProcess::where('part_number', $validatedData['part_number'])
                ->pluck('process_type')
                ->toArray();

            foreach ($request->input('processes') as $process) {
                if (!in_array($process['process_type'], $existingProcesses)) {
                    DB::rollBack();
                    return response()->json([
                        'error' => 'Invalid process type selected',
                        'message' => 'The process "'.$process['process_type'].'" is not valid for this part'
                    ], 400);
                }
            }
        }

        // ✅ Handle file uploads with unique filenames (unique_code + original filename)
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $originalName = $file->getClientOriginalName();
            $fileName = $validatedData['unique_code'] . '_' . $originalName;
            $path = $file->storeAs('attachments', $fileName, 'public');
            $attachmentPath = $fileName; // Store the combined filename
        }

        // Sort processes by their natural order (existing processes first, new ones last)
        $sortedProcesses = collect($request->input('processes'))->sortBy('process_order')->values()->all();

        // ✅ Add staff ID and status
        $requestData = [
            'unique_code' => $validatedData['unique_code'],
            'part_number' => $validatedData['part_number'],
            'part_name' => $validatedData['part_name'],
            'description' => $validatedData['description'] ?? null,
            'attachment' => $attachmentPath,
            'staff_id' => Auth::guard('staff')->id(),
            'status' => 'pending',
            'current_process_index' => 1,
            'total_processes' => count($sortedProcesses),
            'process_type' => $sortedProcesses[0]['process_type'] // Use first process from sorted list
        ];

        // ✅ Save request
        $requestModel = RequestModel::create($requestData);

        if (!$requestModel) {
            throw new \Exception('Failed to create request record');
        }

        // ✅ Create request processes with part_number in the correct order
        foreach ($sortedProcesses as $index => $process) {
            DB::table('request_processes')->insert([
                'unique_code' => $requestModel->unique_code,
                'part_number' => $validatedData['part_number'],
                'process_type' => $process['process_type'],
                'process_order' => $index + 1, // Use sequential order starting from 1
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ✅ Log request creation
        DB::table('request_logs')->insert([
            'unique_code' => $requestModel->unique_code,
            'manager_id' => null,
            'action' => 'created',
            'description' => 'Request has been created by staff ID: ' . $requestData['staff_id'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ✅ Notify managers 1–4
        $staff = Auth::guard('staff')->user();
        $url = route('manager.request.details', ['unique_code' => $requestModel->unique_code]);
        $managers = Manager::whereBetween('manager_number', [1, 4])->get();

        $notifiedManagers = [];
        foreach ($managers as $manager) {
            try {
                $manager->notify(new NewRequestNotification($requestModel, $url, $staff));
                $notifiedManagers[] = $manager->name;
                Log::info('Notification sent to manager', [
                    'manager_id' => $manager->id,
                    'name' => $manager->name
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to notify manager', [
                    'manager_id' => $manager->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // ✅ Commit and broadcast
        DB::commit();
        broadcast(new NewRequestCreated($requestModel))->toOthers();

        return response()->json([
            'success' => 'Request submitted successfully!',
            'request_id' => $requestModel->id,
            'unique_code' => $requestModel->unique_code,
            'notified_managers' => $notifiedManagers,
            'next_step' => route('staff.request.details', $requestModel->unique_code),
            'processes' => $sortedProcesses,
            'attachment_path' => $attachmentPath ? asset('storage/attachments/' . $attachmentPath) : null
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return response()->json([
            'error' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Request submission failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'input' => $request->except(['attachment'])
        ]);

        return response()->json([
            'error' => 'An error occurred while submitting the request',
            'message' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Add a new process type to an existing part
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
   public function addProcessType(Request $request)
{
    $validatedData = $request->validate([
        'part_number' => 'required|string|max:255',
        'process_type' => 'required|string|max:255'
    ]);

    DB::beginTransaction();

    try {
        // Check if part exists
        $part = Part::where('part_number', $validatedData['part_number'])->first();
        if (!$part) {
            return response()->json(['error' => 'Part not found'], 404);
        }

        // Check if process already exists for this part
        $existingProcess = PartProcess::where('part_number', $validatedData['part_number'])
            ->where('process_type', $validatedData['process_type'])
            ->first();

        if ($existingProcess) {
            return response()->json([
                'error' => 'Process type already exists for this part',
                'process' => $existingProcess
            ], 400);
        }

        // Get the next process order
        $lastProcess = PartProcess::where('part_number', $validatedData['part_number'])
            ->orderBy('process_order', 'desc')
            ->first();

        $nextOrder = $lastProcess ? $lastProcess->process_order + 1 : 1;

        // Create the new process
        $newProcess = PartProcess::create([
            'part_number' => $validatedData['part_number'],
            'process_type' => $validatedData['process_type'],
            'process_order' => $nextOrder
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Process type added successfully',
            'process' => [
                'process_type' => $newProcess->process_type,
                'process_order' => $newProcess->process_order
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Failed to add process type', [
            'error' => $e->getMessage(),
            'input' => $validatedData
        ]);

        return response()->json([
            'error' => 'Failed to add process type',
            'message' => $e->getMessage()
        ], 500);
    }
}

    public function getProcessTypes($partNumber)
    {
        // Retrieve the process types from the part_processes table where the part number matches
        $processTypes = PartProcess::where('part_number', $partNumber)
            ->orderBy('process_order')
            ->get();
        
        // Return the process types as JSON
        return response()->json($processTypes);
    }

    /**
     * Create a new part in the database with processes
     *
     * @param string $partNumber
     * @param string $partName
     * @param string|null $description
     * @param array $processTypes
     * @return Part
     */
    protected function createNewPart(string $partNumber, string $partName, ?string $description = null, array $processTypes = []): Part
    {
        DB::beginTransaction();
        
        try {
            // Create the part
            $part = Part::create([
                'part_number' => $partNumber,
                'part_name' => $partName,
                'description' => $description,
                'created_by' => Auth::guard('staff')->id()
            ]);
    
            if (!$part) {
                throw new \Exception('Failed to create part record');
            }
    
            // Create processes for the new part
            foreach ($processTypes as $process) {
                PartProcess::create([
                    'part_number' => $partNumber,
                    'process_type' => $process['type'],
                    'process_order' => $process['order']
                ]);
            }
    
            DB::commit();
            return $part;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Part creation failed', [
                'part_number' => $partNumber,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function show($id)
    {
        $request = RequestModel::findOrFail($id);
    
        return view('staff.requests.show', [
            'request' => $request,
            'excelUrl' => $request->attachment ? asset('storage/' . $request->attachment) : null,
        ]);
    }

    public function destroy($id)
    {
        try {
            $requestModel = RequestModel::find($id);

            if (!$requestModel) {
                return redirect()->route('staff.dashboard')->with('error', 'Request not found.');
            }

            // Delete associated files
            if ($requestModel->attachment) {
                Storage::disk('public')->delete($requestModel->attachment);
            }

            // Delete the request
            $requestModel->delete();

            return redirect()->route('staff.dashboard')->with('success', 'Request deleted successfully.');

        } catch (\Exception $e) {
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
            $validatedData = $request->validate([
                'unique_code'               => 'required|string|max:255',
                'part_number'               => 'required|string|max:255',
                'description'               => 'nullable|string|max:255',
                'attachment'                => 'nullable|file|mimes:xls,xlsx,xlsb|max:20480',
            ]);

            $requestModel = RequestModel::findOrFail($id);

            // ✅ Capture rejected managers before resetting status
            $rejectedManagers = [];
            for ($i = 1; $i <= 4; $i++) {
                $managerColumn = "manager_{$i}_status";
                if ($requestModel->$managerColumn === 'rejected') {
                    $rejectedManagers[] = $i;
                }
            }

            // ✅ Handle attachment removals
            if ($request->has('remove_attachment') && $requestModel->attachment) {
                Storage::disk('public')->delete('attachments/' . $requestModel->attachment);
                $requestModel->attachment = null;
            }

            // ✅ Handle new attachment uploads
            if ($request->hasFile('attachment')) {
                $originalFileName = $request->file('attachment')->getClientOriginalName();
                $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
                $fileNameWithoutExt = pathinfo($originalFileName, PATHINFO_FILENAME);

                // Check if the current attachment exists and has the same base name
                if ($requestModel->attachment) {
                    $currentFileName = $requestModel->attachment;
                    
                    // Delete old attachment
                    Storage::disk('public')->delete('attachments/' . $currentFileName);
                    
                    // Check if the new file has the same base name as the current one
                    if (strpos($currentFileName, $requestModel->unique_code . '_' . $fileNameWithoutExt) === 0) {
                        // This is a revision of the same file
                        // Extract revision number if exists
                        $pattern = '/^' . preg_quote($requestModel->unique_code . '_' . $fileNameWithoutExt, '/') . '(?:\((\d+)\))?\.' . preg_quote($fileExtension, '/') . '$/';
                        preg_match($pattern, $currentFileName, $matches);
                        
                        $revisionNumber = isset($matches[1]) ? (int)$matches[1] + 1 : 2;
                        $newFileName = $requestModel->unique_code . '_' . $fileNameWithoutExt . '(' . $revisionNumber . ').' . $fileExtension;
                    } else {
                        // Completely different file
                        $newFileName = $requestModel->unique_code . '_' . $fileNameWithoutExt . '.' . $fileExtension;
                    }
                } else {
                    // First time attachment
                    $newFileName = $requestModel->unique_code . '_' . $fileNameWithoutExt . '.' . $fileExtension;
                }

                // Store the new file
                $request->file('attachment')->storeAs('attachments', $newFileName, 'public');
                $requestModel->attachment = $newFileName;
            }

            // ✅ Update the request model fields
            $requestModel->unique_code = $validatedData['unique_code'];
            $requestModel->part_number = $validatedData['part_number'];
            $requestModel->description = $validatedData['description'] ?? null;

            // ✅ Reset previously rejected statuses to pending
            foreach ($rejectedManagers as $i) {
                $managerColumn = "manager_{$i}_status";
                $requestModel->$managerColumn = 'pending';
            }

            // ✅ Mark as edited
            $requestModel->is_edited = true;

            // ✅ Save the updated request
            $requestModel->save();

            // ✅ Move to final requests if all approved
            $this->requestService->moveCompletedRequestToFinal($requestModel->id);

            // ✅ Notify managers who had previously rejected
            foreach ($rejectedManagers as $i) {
                $manager = Manager::find($i);

                if ($manager) {
                    // Log the manager details
                    Log::info("Sending updated notification to Manager {$i} (ID: {$manager->id}, Email: {$manager->email})");

                    // Check if manager email exists before sending notification
                    if ($manager->email) {
                        try {
                            $manager->notify(new \App\Notifications\UpdatedNotification(
                                $requestModel,
                                route('manager.request.details', $requestModel->unique_code),
                                $i
                            ));
                            Log::info('Notification sent to manager ' . $manager->id);
                        } catch (\Exception $e) {
                            Log::error('Error sending notification to manager ' . $manager->id . ': ' . $e->getMessage());
                        }
                    } else {
                        Log::warning("Manager {$i} is missing an email, notification not sent.");
                    }
                } else {
                    Log::warning("Manager {$i} not found.");
                }
            }

            return response()->json(['success' => true, 'message' => 'Request updated successfully and managers notified.']);

        } catch (\Exception $e) {
            Log::error('Error updating request:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}