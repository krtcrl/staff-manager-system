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

        // Check if the email exists in the staff table
        if (Staff::where('email', $email)->exists()) {
            if (Auth::guard('staff')->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect('/staff/dashboard');
            }
        }

        // Check if the email exists in the managers table
        elseif (Manager::where('email', $email)->exists()) {
            if (Auth::guard('manager')->attempt($credentials)) {
                $manager = Auth::guard('manager')->user();

                // Check if the manager is a final request manager
                if (in_array($manager->manager_number, [5, 6, 7, 8, 9])) {
                    $request->session()->regenerate();
                    return redirect('/manager/final-dashboard');
                } else {
                    $request->session()->regenerate();
                    return redirect('/manager/dashboard');
                }
            }
        }

        // Display only inline error under the email field
        return back()->withErrors([
            'email' => 'These credentials do not match our records.'
        ])->onlyInput('email');
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
