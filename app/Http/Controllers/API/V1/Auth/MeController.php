<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Auth\AuthResource;
use Illuminate\Http\Request;

class MeController extends Controller
{
    /**
     * Get the authenticated user.
     */
    public function me(Request $request)
    {
        return new AuthResource(['user' => $request->user()]);
    }
}
