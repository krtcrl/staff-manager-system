<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestModel; // Ensure this is the correct model for the 'request' table
use App\Models\Notification; // Add the Notification model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ManagerController extends Controller
{
    public function index()
    {
        // Fetch all requests with the required fields
        $requests = RequestModel::select(
            'unique_code',
            'description',
            'manager_1_status',
            'manager_2_status',
            'manager_3_status',
            'manager_4_status'
        )->get();

// Fetch unread notifications for the logged-in manager
$notifications = Notification::where('user_id', Auth::guard('manager')->id())
->where('read', false)
->orderBy('created_at', 'desc')
->take(10)
->get();


        // Pass the data to the view
        return view('manager.manager_main', compact('requests', 'notifications'));
    }

    public function show($unique_code)
    {
        // Fetch the request details by unique_code
        $request = RequestModel::where('unique_code', $unique_code)->first();

        // If the request is not found, return a 404 error
        if (!$request) {
            abort(404);
        }

        // Calculate the number of managers who have approved, rejected, or marked the request as pending
        $approvedManagers = [];
        $rejectedManagers = [];
        $pendingManagers = [];

        for ($i = 1; $i <= 4; $i++) {
            $status = $request->{'manager_' . $i . '_status'};
            if ($status === 'approved') {
                $approvedManagers[] = 'Manager ' . $i;
            } elseif ($status === 'rejected') {
                $rejectedManagers[] = 'Manager ' . $i;
            } else {
                $pendingManagers[] = 'Manager ' . $i;
            }
        }

        // Pass the request details and manager status counts to the view
        return view('manager.request_details', compact('request', 'approvedManagers', 'rejectedManagers', 'pendingManagers'));
    }

    public function approve(Request $request, $unique_code)
    {
        // Get the logged-in manager
        $manager = Auth::guard('manager')->user();

        // Log the manager's details for debugging
        Log::info('Logged-in Manager:', [
            'id' => $manager->id,
            'name' => $manager->name,
            'manager_number' => $manager->manager_number,
        ]);

        // Find the request by unique_code
        $request = RequestModel::where('unique_code', $unique_code)->firstOrFail();

        // Log the request details for debugging
        Log::info('Request Details Before Update:', [
            'unique_code' => $request->unique_code,
            'manager_1_status' => $request->manager_1_status,
            'manager_2_status' => $request->manager_2_status,
            'manager_3_status' => $request->manager_3_status,
            'manager_4_status' => $request->manager_4_status,
        ]);

        // Update the manager's status based on their manager_number
        $column = 'manager_' . $manager->manager_number . '_status';
        Log::info('Updating Column:', ['column' => $column]);

        // Update the specific manager's status
        $request->update([$column => 'approved']);

        // Log the updated request details
        Log::info('Request Details After Update:', [
            'unique_code' => $request->unique_code,
            'manager_1_status' => $request->manager_1_status,
            'manager_2_status' => $request->manager_2_status,
            'manager_3_status' => $request->manager_3_status,
            'manager_4_status' => $request->manager_4_status,
        ]);

        // Check if all managers have approved the request
        $this->updateOverallStatus($request);

        // Create a notification for the staff member
        Notification::create([
            'user_id' => $request->user_id, // Assuming the request has a user_id field for the staff member
            'type' => 'request_approved',
            'message' => 'Your request (' . $request->unique_code . ') has been approved by Manager ' . $manager->manager_number . '.',
        ]);

        return redirect()->back()->with('success', 'Request approved by manager ' . $manager->manager_number . '!');
    }

    public function reject(Request $request, $unique_code)
    {
        // Get the logged-in manager
        $manager = Auth::guard('manager')->user();

        // Log the manager's details for debugging
        Log::info('Logged-in Manager:', [
            'id' => $manager->id,
            'name' => $manager->name,
            'manager_number' => $manager->manager_number,
        ]);

        // Find the request by unique_code
        $request = RequestModel::where('unique_code', $unique_code)->firstOrFail();

        // Log the request details for debugging
        Log::info('Request Details Before Update:', [
            'unique_code' => $request->unique_code,
            'manager_1_status' => $request->manager_1_status,
            'manager_2_status' => $request->manager_2_status,
            'manager_3_status' => $request->manager_3_status,
            'manager_4_status' => $request->manager_4_status,
        ]);

        // Update the manager's status based on their manager_number
        $column = 'manager_' . $manager->manager_number . '_status';
        Log::info('Updating Column:', ['column' => $column]);

        // Update the specific manager's status
        $request->update([$column => 'rejected']);

        // Log the updated request details
        Log::info('Request Details After Update:', [
            'unique_code' => $request->unique_code,
            'manager_1_status' => $request->manager_1_status,
            'manager_2_status' => $request->manager_2_status,
            'manager_3_status' => $request->manager_3_status,
            'manager_4_status' => $request->manager_4_status,
        ]);

        // Check if all managers have approved the request
        $this->updateOverallStatus($request);

        // Create a notification for the staff member
        Notification::create([
            'user_id' => $request->user_id, // Assuming the request has a user_id field for the staff member
            'type' => 'request_rejected',
            'message' => 'Your request (' . $request->unique_code . ') has been rejected by Manager ' . $manager->manager_number . '.',
        ]);

        return redirect()->back()->with('success', 'Request rejected by manager ' . $manager->manager_number . '!');
    }

    private function updateOverallStatus(RequestModel $request)
    {
        // Check if all managers have approved the request
        if ($request->manager_1_status === 'approved' &&
            $request->manager_2_status === 'approved' &&
            $request->manager_3_status === 'approved' &&
            $request->manager_4_status === 'approved') {
            $request->update(['overall_status' => 'approved']);
        } else {
            $request->update(['overall_status' => 'pending']);
        }
    }
    public function markNotificationsAsRead()
{
    Notification::where('user_id', Auth::guard('manager')->id())
        ->where('read', false)
        ->update(['read' => true]);

    return response()->json(['success' => true]);
}

}