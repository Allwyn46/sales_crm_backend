<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Auth\LoginRequest;
use App\Http\Resources\API\V1\Auth\AuthResource;
use App\Actions\Auth\Authenticate;

class LoginController extends Controller
{
    /**
     * Log a user in and issue a Sanctum token.
     */
    public function login(LoginRequest $request)
    {
        $result = (new Authenticate())->execute(
            $request->validated('email'),
            $request->validated('password')
        );

        return new AuthResource($result);
    }
}
