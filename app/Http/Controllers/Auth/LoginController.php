<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Attempt to log in as a manager
    if (Auth::guard('manager')->attempt($credentials)) {
        \Log::info('Manager login successful for email: ' . $credentials['email']);
        $request->session()->regenerate();
        return redirect('/manager/dashboard');
    } else {
        \Log::error('Manager login failed for email: ' . $credentials['email']);
    }

    // Attempt to log in as a staff member
    if (Auth::guard('staff')->attempt($credentials)) {
        \Log::info('Staff login successful for email: ' . $credentials['email']);
        $request->session()->regenerate();
        return redirect('/staff/dashboard');
    } else {
        \Log::error('Staff login failed for email: ' . $credentials['email']);
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
}


    // Handle the logout request
    public function logout(Request $request)
    {
        if (Auth::guard('manager')->check()) {
            Auth::guard('manager')->logout();
        } elseif (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
        }
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/');
    }
}