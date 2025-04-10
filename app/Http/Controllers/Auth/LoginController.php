<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;
use App\Models\Manager;
use App\Models\SuperAdmin; // Add this line

class LoginController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

// app/Http/Controllers/Auth/LoginController.php
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // DEBUG: Log the input credentials (remove in production)
    \Log::debug('Login attempt', ['email' => $credentials['email']]);

    // SUPER ADMIN AUTHENTICATION
    if ($superAdmin = SuperAdmin::where('email', $credentials['email'])->first()) {
        \Log::debug('SuperAdmin found', ['id' => $superAdmin->id]);
        
        // Manual password verification
        if (\Hash::check($credentials['password'], $superAdmin->password)) {
            Auth::guard('superadmin')->login($superAdmin);
            $request->session()->regenerate();
            \Log::debug('SuperAdmin login successful');
            return redirect()->intended('/superadmin/dashboard');
        }
        
        \Log::warning('SuperAdmin password mismatch', [
            'input' => $credentials['password'],
            'hashed' => $superAdmin->password
        ]);
    }


    
        // Then check Staff
        elseif (Staff::where('email', $email)->exists()) {
            $success = Auth::guard('staff')->attempt($credentials, $request->filled('remember'));
            $this->logAuthAttempt('staff', $email, $success);
    
            if ($success) {
                $request->session()->regenerate();
                return redirect()->intended('/staff/dashboard');
            }
        }
    
        // Then check Manager
        elseif (Manager::where('email', $email)->exists()) {
            $success = Auth::guard('manager')->attempt($credentials, $request->filled('remember'));
            $this->logAuthAttempt('manager', $email, $success);
    
            if ($success) {
                $manager = Auth::guard('manager')->user();
                $request->session()->regenerate();
                return redirect()->intended(
                    in_array($manager->manager_number, [5, 6, 7, 8, 9])
                        ? '/manager/final-dashboard'
                        : '/manager/dashboard'
                );
            }
        }
    
        $this->logAuthAttempt('unknown', $email, false);
        return back()->withErrors([
            'email' => 'These credentials do not match our records.'
        ])->onlyInput('email');
    }
    
    // Handle the logout request
    public function logout(Request $request)
    {
        // Log out the user from all guards
        if (Auth::guard('superadmin')->check()) {
            Auth::guard('superadmin')->logout();
        } elseif (Auth::guard('staff')->check()) {
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
    // Add to your LoginController
protected function logAuthAttempt($guard, $email, $success)
{
    \Log::info('Authentication attempt', [
        'guard' => $guard,
        'email' => $email,
        'success' => $success,
        'ip' => request()->ip(),
        'user_agent' => request()->userAgent()
    ]);
}
// app/Http/Controllers/Auth/LoginController.php
public function showSuperAdminLoginForm()
{
    return view('auth.superadmin-login');
}

public function superAdminLogin(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    \Log::debug('SuperAdmin login attempt', $credentials);

    if (Auth::guard('superadmin')->attempt($credentials)) {
        $request->session()->regenerate();
        \Log::debug('SuperAdmin login successful', ['user' => Auth::guard('superadmin')->user()]);
        return redirect()->intended('/superadmin/dashboard');
    }

    \Log::warning('SuperAdmin login failed', [
        'email' => $credentials['email'],
        'error' => 'Invalid credentials'
    ]);

    return back()->withErrors([
        'email' => 'These credentials do not match our super admin records.'
    ])->onlyInput('email');
}
}