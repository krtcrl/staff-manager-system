<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // For manager notifications (keeps your existing route working)
    public function index()
    {
        // Check which guard is authenticated
        if (auth()->guard('manager')->check()) {
            return view('manager.notifications.index', [
                'notifications' => auth()->guard('manager')->user()->notifications()->paginate(10)
            ]);
        }
        
        // Fallback for staff if needed
        return view('staff.notifications.index', [
            'notifications' => auth()->guard('staff')->user()->notifications()->paginate(10)
        ]);
    }

    // Mark as read for both roles
    public function markAsRead(Request $request)
    {
        $user = auth()->guard('manager')->check() 
            ? auth()->guard('manager')->user()
            : auth()->guard('staff')->user();

        $user->unreadNotifications
            ->when($request->input('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })
            ->markAsRead();

        return response()->noContent();
    }
}