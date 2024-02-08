<?php

namespace App\Kravanh\Application\Member\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutManagerController
{
    public function __invoke(Request $request): JsonResponse
    {

        return asJson([
            'online' => $request->user()->online
        ]);
    }
}
