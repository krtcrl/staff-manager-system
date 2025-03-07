<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Manager;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    // Validate the request
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:staff,email', 'unique:managers,email'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role' => ['required', 'in:staff,manager'], // Ensure the role is either 'staff' or 'manager'
    ]);

    // Create the user based on the selected role
    if ($request->role === 'staff') {
        $user = Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Log in the staff user
        Auth::guard('staff')->login($user);

        // Redirect to the staff dashboard
        return redirect()->route('staff.dashboard');
    } elseif ($request->role === 'manager') {
        $user = Manager::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Log in the manager user
        Auth::guard('manager')->login($user);

        // Redirect to the manager dashboard
        return redirect()->route('manager.dashboard');
    }

    // Fallback redirect (should not reach here)
    return redirect(route('dashboard'));
}
}