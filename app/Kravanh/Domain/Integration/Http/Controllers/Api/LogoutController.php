<?php

namespace App\Kravanh\Domain\Integration\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutController
{
    public function __invoke(
        Request $request
    ): JsonResponse
    {
        $user = $request->user();

        $user->currentAccessToken()->delete();

        return redirectSucceed('Logged out successfully');
    }
} 