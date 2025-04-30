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

    try {
        // ✅ Validate incoming request
        $validatedData = $request->validate([
            'unique_code' => 'required|string|max:255',
            'part_number' => 'required|string|max:255',
            'part_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'attachment' => 'required|file|mimes:xls,xlsx,xlsb|max:20480',
            //'final_approval_attachment' => 'required|file|mimes:xls,xlsx,xlsb|max:20480',
            'is_new_part' => 'sometimes|string|in:true,false,1,0',
            'process_types' => 'sometimes|array',
            'process_types.*.type' => 'required|string|max:255',
            'process_types.*.order' => 'required|integer|min:1'
        ]);

        // Convert is_new_part to boolean
        $isNewPart = filter_var($request->input('is_new_part'), FILTER_VALIDATE_BOOLEAN);

        // ✅ Check if this is a new part and needs to be created
        if ($isNewPart) {
            $processTypes = $request->input('process_types', []);
            
            // Validate at least one process type exists for new parts
            if (empty($processTypes)) {
                DB::rollBack();
                return response()->json([
                    'error' => 'At least one process type is required for new parts.',
                    'suggestion' => 'Please add process types for this new part'
                ], 400);
            }

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

            // Create processes for the new part
            foreach ($processTypes as $process) {
                PartProcess::create([
                    'part_number' => $validatedData['part_number'],
                    'process_type' => $process['type'],
                    'process_order' => $process['order']
                ]);
            }
        }

        // ✅ Fetch all processes for the part number
        $processes = DB::table('part_processes')
            ->where('part_number', $validatedData['part_number'])
            ->orderBy('process_order')
            ->get();

        if ($processes->isEmpty()) {
            DB::rollBack();
            return response()->json([
                'error' => 'No processes found for the selected part number.',
                'suggestion' => 'Please contact admin to set up processes for this part'
            ], 400);
        }

        // ✅ Set process-related data
        $validatedData['process_type'] = $processes->first()->process_type;
        $validatedData['current_process_index'] = 1;
        $validatedData['total_processes'] = $processes->unique('process_order')->count();

        // ✅ Handle file uploads with unique filenames
        $filePrefix = strtoupper(substr($validatedData['unique_code'], 0, 5)) . '_';
        
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = $filePrefix . time() . '_' . $file->getClientOriginalName();
            $file->storeAs('attachments', $fileName, 'public');
            $validatedData['attachment'] = $fileName;
        }

        //if ($request->hasFile('final_approval_attachment')) {
        //    $file = $request->file('final_approval_attachment');
        //    $fileName = $filePrefix . time() . '_' . $file->getClientOriginalName();
        //    $file->storeAs('final_approval_attachments', $fileName, 'public');
        //    $validatedData['final_approval_attachment'] = $fileName;
        //}

        // ✅ Add staff ID and status
        $validatedData['staff_id'] = Auth::guard('staff')->id();
        $validatedData['status'] = 'pending';

        // ✅ Save request
        $requestModel = RequestModel::create($validatedData);

        if (!$requestModel) {
            throw new \Exception('Failed to create request record');
        }

        // ✅ Log request creation
        DB::table('request_logs')->insert([
            'unique_code' => $requestModel->unique_code,
            'manager_id' => null,
            'action' => 'created',
            'description' => 'Request has been created by staff ID: ' . $validatedData['staff_id'],
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
            'next_step' => route('staff.request.details', $requestModel->unique_code)
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
           // 'input' => $request->except(['attachment', 'final_approval_attachment'])

        ]);

        return response()->json([
            'error' => 'An error occurred while submitting the request',
            'message' => $e->getMessage()
        ], 500);
    }
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

    /**
     * Create default processes for a new part
     *
     * @param Part $part
     */
    
    
    public function show($id)
    {
        $request = RequestModel::findOrFail($id);
    
        return view('staff.requests.show', [
            'request' => $request,
            'excelUrl' => $request->attachment ? asset('storage/' . $request->attachment) : null,
          //  'finalExcelUrl' => $request->final_approval_attachment ? asset('storage/' . $request->final_approval_attachment) : null
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
          //  if ($requestModel->final_approval_attachment) {
           //     Storage::disk('public')->delete($requestModel->final_approval_attachment);
           // }

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
             //   'final_approval_attachment' => 'nullable|file|mimes:xls,xlsx,xlsb|max:20480',
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
    
            //if ($request->has('remove_final_approval_attachment') && $requestModel->final_approval_attachment) {
            //    Storage::disk('public')->delete('final_approval_attachments/' . $requestModel->final_approval_attachment);
             //   $requestModel->final_approval_attachment = null;
            //}
    
            // ✅ Handle new attachment uploads
            if ($request->hasFile('attachment')) {
                if ($requestModel->attachment) {
                    Storage::disk('public')->delete('attachments/' . $requestModel->attachment);
                }
    
                $originalFileName = $request->file('attachment')->getClientOriginalName();
                $request->file('attachment')->storeAs('attachments', $originalFileName, 'public');
                $requestModel->attachment = $originalFileName;
            }
    
           // if ($request->hasFile('final_approval_attachment')) {
            //    if ($requestModel->final_approval_attachment) {
            //        Storage::disk('public')->delete('final_approval_attachments/' . $requestModel->final_approval_attachment);
            //    }
    
            //    $originalFinalApprovalFileName = $request->file('final_approval_attachment')->getClientOriginalName();
             //   $request->file('final_approval_attachment')->storeAs('final_approval_attachments', $originalFinalApprovalFileName, 'public');
             //   $requestModel->final_approval_attachment = $originalFinalApprovalFileName;
            //}
    
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