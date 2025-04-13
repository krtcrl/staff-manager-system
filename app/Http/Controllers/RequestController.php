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
                'final_approval_attachment' => 'nullable|file|mimes:xls,xlsx,xlsb|max:20480',
            ]);
    
            // ✅ Fetch all processes for the part number
            $processes = DB::table('part_processes')
                ->where('part_number', $validatedData['part_number'])
                ->orderBy('process_order')
                ->get();
    
            if ($processes->isEmpty()) {
                DB::rollBack();
                return response()->json(['error' => 'No processes found for the selected part number.'], 400);
            }
    
            // ✅ Set process-related data
            $validatedData['process_type'] = $processes->first()->process_type;
            $validatedData['current_process_index'] = 1;
            $validatedData['total_processes'] = $processes->unique('process_order')->count();
    
            // ✅ Handle attachment file
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $originalName = $file->getClientOriginalName();
                $file->storeAs('attachments', $originalName, 'public');
                $validatedData['attachment'] = $originalName;
            }
    
            // ✅ Handle final approval file
            if ($request->hasFile('final_approval_attachment')) {
                $file = $request->file('final_approval_attachment');
                $originalName = $file->getClientOriginalName();
                $file->storeAs('final_approval_attachments', $originalName, 'public');
                $validatedData['final_approval_attachment'] = $originalName;
            }
    
            // ✅ Add staff ID
            $validatedData['staff_id'] = Auth::guard('staff')->id();
    
            // ✅ Save request
            $requestModel = RequestModel::create($validatedData);
    
            if ($requestModel) {
                // ✅ Log request creation
                DB::table('request_logs')->insert([
                    'unique_code' => $requestModel->unique_code,
                    'manager_id' => null,
                    'action' => 'created',
                    'description' => 'Request has been created by staff.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
    
                // ✅ Notify managers 1–4
                $staff = Auth::guard('staff')->user();
                $url = route('manager.request.details', ['unique_code' => $requestModel->unique_code]);
                $managers = Manager::whereBetween('manager_number', [1, 4])->get();
    
                foreach ($managers as $manager) {
                    try {
                        Log::debug('Sending notification to manager', ['manager_id' => $manager->id]);
                        $manager->notify(new NewRequestNotification($requestModel, $url, $staff));
                        Log::debug('Notification sent successfully', ['manager_id' => $manager->id]);
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
                    'request' => $requestModel,
                    'notified_managers' => $managers->pluck('name')
                ], 201);
            }
    
            DB::rollBack();
            return response()->json(['error' => 'Failed to submit request.'], 500);
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store method failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            return response()->json([
                'error' => 'An error occurred while submitting the request.',
                'details' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    public function show($id)
    {
        $request = RequestModel::findOrFail($id);
    
        return view('staff.requests.show', [
            'request' => $request,
            'excelUrl' => $request->attachment ? asset('storage/' . $request->attachment) : null,
            'finalExcelUrl' => $request->final_approval_attachment ? asset('storage/' . $request->final_approval_attachment) : null
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
            if ($requestModel->final_approval_attachment) {
                Storage::disk('public')->delete($requestModel->final_approval_attachment);
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
                'final_approval_attachment' => 'nullable|file|mimes:xls,xlsx,xlsb|max:20480',
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
    
            if ($request->has('remove_final_approval_attachment') && $requestModel->final_approval_attachment) {
                Storage::disk('public')->delete('final_approval_attachments/' . $requestModel->final_approval_attachment);
                $requestModel->final_approval_attachment = null;
            }
    
            // ✅ Handle new attachment uploads
            if ($request->hasFile('attachment')) {
                if ($requestModel->attachment) {
                    Storage::disk('public')->delete('attachments/' . $requestModel->attachment);
                }
    
                $originalFileName = $request->file('attachment')->getClientOriginalName();
                $request->file('attachment')->storeAs('attachments', $originalFileName, 'public');
                $requestModel->attachment = $originalFileName;
            }
    
            if ($request->hasFile('final_approval_attachment')) {
                if ($requestModel->final_approval_attachment) {
                    Storage::disk('public')->delete('final_approval_attachments/' . $requestModel->final_approval_attachment);
                }
    
                $originalFinalApprovalFileName = $request->file('final_approval_attachment')->getClientOriginalName();
                $request->file('final_approval_attachment')->storeAs('final_approval_attachments', $originalFinalApprovalFileName, 'public');
                $requestModel->final_approval_attachment = $originalFinalApprovalFileName;
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