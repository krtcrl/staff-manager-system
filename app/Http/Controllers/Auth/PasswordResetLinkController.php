<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Manager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Notifications\CustomResetPassword; // Add this import


class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);
    
        // Check both tables
        $user = Staff::where('email', $request->email)->first();
        $isManager = false;
        
        if (!$user) {
            $user = Manager::where('email', $request->email)->first();
            $isManager = true;
        }
    
        if (!$user) {
            return back()->withErrors(['email' => __('We can\'t find a user with that email address.')]);
        }
    
        // Generate token (with 60 character length)
        $token = Str::random(60);
        
        $user->forceFill([
            'reset_token' => Hash::make($token),
            'reset_token_created_at' => now(),
        ])->save();
    
        $user->notify(new CustomResetPassword($token, $isManager));
    
        return back()->with('status', __('Password reset link sent!'));
    }
}