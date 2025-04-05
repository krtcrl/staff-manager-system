<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Manager notifications
    public function managerIndex()
    {
        $manager = auth()->guard('manager')->user();

        return view('manager.notifications.index', [
            'notifications' => $manager->notifications()->paginate(10)
        ]);
    }

    // Staff notifications
    public function staffIndex()
    {
        $staff = auth()->guard('staff')->user();

        return view('staff.notifications.index', [
            'notifications' => $staff->notifications()->paginate(10)
        ]);
    }

    // Mark as read for manager
    public function managerMarkAsRead(Request $request)
    {
        $manager = auth()->guard('manager')->user();

        $manager->unreadNotifications
            ->when($request->input('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })
            ->markAsRead();

        return response()->noContent();
    }

    // Mark as read for staff
    public function staffMarkAsRead(Request $request)
    {
        $staff = auth()->guard('staff')->user();

        $staff->unreadNotifications
            ->when($request->input('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })
            ->markAsRead();

        return response()->noContent();
    }
}
