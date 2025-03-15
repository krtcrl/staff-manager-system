<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinalManager; // Assuming you have a FinalManager model

class FinalManagerController extends Controller
{
    // Display the Final Approval Manager dashboard
    public function index()
    {
        // Fetch data for the dashboard (e.g., pending requests, approved requests, etc.)
        $pendingRequests = Request::where('status', 'pending')->get();
        $approvedRequests = Request::where('status', 'approved')->get();

        return view('finalmanager.dashboard', [
            'pendingRequests' => $pendingRequests,
            'approvedRequests' => $approvedRequests,
        ]);
    }

    // Display the request list for Final Approval Managers
    public function requestList()
    {
        // Fetch all requests that need final approval
        $requests = Request::where('status', 'pending')->get();

        return view('finalmanager.request-list', [
            'requests' => $requests,
        ]);
    }

    // Approve a request (if applicable)
    public function approveRequest($id)
    {
        $request = Request::findOrFail($id);

        // Update the request status to "approved"
        $request->status = 'approved';
        $request->save();

        return redirect()->route('finalmanager.request-list')->with('success', 'Request approved successfully.');
    }

    // Reject a request (if applicable)
    public function rejectRequest($id)
    {
        $request = Request::findOrFail($id);

        // Update the request status to "rejected"
        $request->status = 'rejected';
        $request->save();

        return redirect()->route('finalmanager.request-list')->with('success', 'Request rejected successfully.');
    }
}