<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Manager; // For Pre-Approval Managers
use App\Models\FinalManager; // For Final Approval Managers
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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:staff,email', 'unique:managers,email', 'unique:finalmanagers,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:staff,manager'], // Ensure the role is either 'staff' or 'manager'
            'manager_type' => ['nullable', 'in:pre_approval,final_approval'], // Only required if role is manager
        ]);

        // Create the user based on the selected role
        if ($request->role === 'staff') {
            $user = Staff::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Log in the staff user
            Auth::guard('staff')->login($user);

            // Redirect to the staff dashboard
            return redirect()->route('staff.dashboard');
        } elseif ($request->role === 'manager') {
            // Validate manager type if role is manager
            if (!$request->manager_type) {
                return redirect()->back()->withErrors(['manager_type' => 'Please select a manager type.'])->withInput();
            }

            // Create the manager based on the selected type
            if ($request->manager_type === 'pre_approval') {
                $user = Manager::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                // Log in the pre-approval manager
                Auth::guard('manager')->login($user);
            } elseif ($request->manager_type === 'final_approval') {
                $user = FinalManager::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                // Log in the final approval manager
                Auth::guard('finalmanager')->login($user);
            }

            // Redirect to the manager dashboard
            return redirect()->route('manager.dashboard');
        }

        // Fallback redirect (should not reach here)
        return redirect(route('dashboard'));
    }
}