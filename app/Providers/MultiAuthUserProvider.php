<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Str;
use App\Models\Staff;
use App\Models\Manager;

class MultiAuthUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        // Check both tables
        $user = Staff::where('email', $identifier)->first();
        if (!$user) {
            $user = Manager::where('email', $identifier)->first();
        }
        return $user;
    }

    public function retrieveByToken($identifier, $token)
    {
        // Implementation for remember token
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // Implementation for remember token
    }

    public function retrieveByCredentials(array $credentials)
    {
        $email = $credentials['email'];
        
        $user = Staff::where('email', $email)->first();
        if (!$user) {
            $user = Manager::where('email', $email)->first();
        }
        return $user;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // Password validation logic
        return true;
    }
}