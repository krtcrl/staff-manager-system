<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Staff;
use App\Models\Manager;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // Attempt login for staff
        if (Auth::guard('staff')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->route('staff.dashboard');
        }
    
        // Attempt login for manager
        if (Auth::guard('manager')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->route('manager.dashboard');
        }
    
        // Attempt login for final manager
        if (Auth::guard('finalmanager')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->route('finalmanager.dashboard'); // Ensure this route exists
        }
    
        // If authentication fails
        throw ValidationException::withMessages([
            'email' => ['These credentials do not match our records.'],
        ]);
    }
    
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
{
    if (Auth::guard('staff')->check()) {
        Auth::guard('staff')->logout();
    } elseif (Auth::guard('manager')->check()) {
        Auth::guard('manager')->logout();
    }

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
}

}
