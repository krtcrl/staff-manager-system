<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestModel;
use App\Models\Notification;
use App\Models\Manager;
use Illuminate\Support\Facades\Auth;
use App\Events\NewRequestNotification;

use App\Events\NewRequestCreated;

use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Str;


class RequestController extends Controller
{
    public function store(Request $request)
{
    // Validate the request data
    $validatedData = $request->validate([
        'unique_code' => 'required|string|max:255',
        'part_number' => 'required|string|max:255',
        'part_name' => 'required|string|max:255',
        'process_type' => 'required|string|max:255',
        'uph' => 'required|integer',
        'description' => 'nullable|string',
        'status' => 'required|string|max:255',
    ]);

    // Insert the data into the database
    $requestModel = RequestModel::create($validatedData);

    if ($requestModel) {
        // Notify all managers (1-4)
        $managers = Manager::whereIn('manager_number', [1, 2, 3, 4])->get();

        foreach ($managers as $manager) {
            Notification::create([
                'user_id' => $manager->id,
                'type' => 'new_request',
                'message' => 'A new request (' . $requestModel->unique_code . ') has been submitted.',
                'read' => false,
            ]);
        }

        // Broadcast the event to notify all users in real-time
        broadcast(new NewRequestCreated($requestModel))->toOthers();

        return response()->json(['success' => 'Request submitted successfully!', 'request' => $requestModel]);
    } else {
        return response()->json(['error' => 'Failed to submit request.'], 500);
    }
}

    public function destroy($id)
    {
        // Find the request in the database
        $requestModel = RequestModel::find($id);

        if (!$requestModel) {
            return redirect()->route('staff.dashboard')->with('error', 'Request not found.');
        }

        // Delete the request
        $requestModel->delete();

        return redirect()->route('staff.dashboard')->with('success', 'Request deleted successfully.');
    }
}
