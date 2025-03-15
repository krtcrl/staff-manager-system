<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;
use App\Models\Manager;
use App\Models\FinalManager;

class LoginController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle the login request
    public function login(Request $request)
    {
        // Validate the request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $email = $credentials['email'];
    
        // Debug: Log the email being checked
        \Log::info('Attempting login for email: ' . $email);
    
        // Check if the email exists in the staff table
        if (Staff::where('email', $email)->exists()) {
            \Log::info('Email found in staff table');
            if (Auth::guard('staff')->attempt($credentials)) {
                \Log::info('Staff login successful');
                $request->session()->regenerate();
                return redirect('/staff/dashboard');
            } else {
                \Log::error('Staff login failed');
            }
        }
    
        // Check if the email exists in the managers table
        elseif (Manager::where('email', $email)->exists()) {
            \Log::info('Email found in managers table');
            if (Auth::guard('manager')->attempt($credentials)) {
                \Log::info('Manager login successful');
                $request->session()->regenerate();
                return redirect('/manager/dashboard');
            } else {
                \Log::error('Manager login failed');
            }
        }
    
        elseif (FinalManager::where('email', $email)->exists()) {
            \Log::info('Email found in finalmanagers table');
        
            $finalManager = FinalManager::where('email', $email)->first();
            \Log::info('Stored Hashed Password: ' . $finalManager->password);
        
            if (\Illuminate\Support\Facades\Hash::check($credentials['password'], $finalManager->password)) {
                \Log::info('Password matches!');
        
                if (Auth::guard('finalmanager')->attempt($credentials)) {
                    \Log::info('Final Approval Manager login successful');
                    $request->session()->regenerate();
                    return redirect('/finalmanager/dashboard');
                } else {
                    \Log::error('Guard authentication failed');
                }
            } else {
                \Log::error('Password mismatch!');
            }
        }
        
        
    
        // Debug: Log if email is not found in any table
        \Log::error('Email not found in any table: ' . $email);
    
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    // Handle the logout request
    public function logout(Request $request)
    {
        // Log out the user from all guards
        if (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
        } elseif (Auth::guard('manager')->check()) {
            Auth::guard('manager')->logout();
        } elseif (Auth::guard('finalmanager')->check()) {
            Auth::guard('finalmanager')->logout();
        }

        // Invalidate the session and regenerate the token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the home page
        return redirect('/');
    }
}