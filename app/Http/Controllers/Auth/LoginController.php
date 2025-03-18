<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;
use App\Models\Manager;

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
    
                // Get the logged-in manager
                $manager = Auth::guard('manager')->user();
    
                // Check if the manager is a final request manager (manager_number 5, 6, 7, 8, or 9)
                if (in_array($manager->manager_number, [5, 6, 7, 8, 9])) {
                    \Log::info('Final Request Manager login successful');
                    $request->session()->regenerate();
                    return redirect('/manager/final-dashboard'); // Redirect to final request dashboard
                } else {
                    \Log::info('Regular Manager login successful');
                    $request->session()->regenerate();
                    return redirect('/manager/dashboard'); // Redirect to regular manager dashboard
                }
            } else {
                \Log::error('Manager login failed');
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
        }

        // Invalidate the session and regenerate the token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the home page
        return redirect('/');
    }
}