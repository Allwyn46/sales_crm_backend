<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class Authenticate
{
    /**
     * Authenticate a user and issue a Sanctum personal access token.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function execute(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $token = $user->createToken('auth_token');

        return [
            'user' => $user,
            'token' => $token->plainTextToken,
        ];
    }
}
