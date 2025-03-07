<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();

        if ($user) {
            if (($role === 'staff' && $user->table === 'staff') || 
                ($role === 'manager' && $user->table === 'managers')) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action.');
    }
}
