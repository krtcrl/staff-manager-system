<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Log;

class NewPasswordController extends Controller
{
    public function create(Request $request)
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);
    
        // Check both tables
        $user = Staff::where('email', $request->email)->first();
        $isManager = false;
        
        if (!$user) {
            $user = Manager::where('email', $request->email)->first();
            $isManager = true;
        }
    
        // Validate token
        $tokenValid = $user && 
                     $user->reset_token &&
                     Hash::check($request->token, $user->reset_token) &&
                     $user->reset_token_created_at &&
                     $user->reset_token_created_at->addHour()->isFuture();
    
        if (!$tokenValid) {
            return back()->withErrors(['email' => __('Invalid or expired token.')]);
        }
    
        // Update password
        $user->forceFill([
            'password' => Hash::make($request->password),
            'reset_token' => null,
            'reset_token_created_at' => null,
        ])->save();
    
        return redirect()->route('login')->with('status', __('Password reset successfully!'));
    }
}