<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinalManager; // Assuming you have a FinalManager model
use App\Models\FinalRequest;

class FinalManagerController extends Controller
{
    public function index()
{
    // Fetch all final requests
    $finalRequests = FinalRequest::all(); // Use `all()` instead of `paginate()` for simplicity

    return view('finalmanager.finalmanager_main', compact('finalRequests'));
}
public function show($unique_code)
{
    // Fetch the final request details by unique_code
    $finalRequest = FinalRequest::where('unique_code', $unique_code)->first();

    // If the request is not found, return a 404 error
    if (!$finalRequest) {
        abort(404);
    }

    return view('finalmanager.request_details', compact('finalRequest'));
}
}