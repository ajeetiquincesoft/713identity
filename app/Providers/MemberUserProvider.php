<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class MemberUserProvider implements UserProvider
{
    public function retrieveByToken ($identifier, $token) {
        throw new Exception('Method not implemented.');
    }

    public function updateRememberToken (Authenticatable $user, $token) {
        throw new Exception('Method not implemented.');
    }

    public function retrieveById ($identifier) {
        return User::find($identifier);
    }

    public function retrieveByCredentials (array $credentials) {
        $phone = $credentials['phone'];

        return User::where('phone', $phone)->first();
    }

    public function validateCredentials (Authenticatable $user, array $credentials) {
        $otp = $credentials['otp'];
        $phone = $credentials['phone'];
        $status = $credentials['status'];

        return User::where('phone', $phone)->where('otp',$otp)->where('status',$status)->first();
    }
}
