<?php

namespace App\Kravanh\Domain\Integration\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MarkAsMemberOfflineController
{
    public function __invoke(
        Request $request
    ): JsonResponse
    {
        $user = $request->user();
       
        $user->markAsOffline();

        return redirectSucceed('Mark as offline successfully.');
    }
} 