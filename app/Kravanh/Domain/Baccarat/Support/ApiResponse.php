<?php

namespace App\Kravanh\Domain\Baccarat\Support;

use Illuminate\Http\JsonResponse;
use Response;

final class ApiResponse
{
    const Ok = 'ok';

    const Failed = 'failed';

    public static function ok(string $message): JsonResponse
    {
        return Response::json([
            'type' => self::Ok,
            'message' => $message,
        ]);

    }

    public static function failed(string $message): JsonResponse
    {
        return Response::json([
            'type' => self::Failed,
            'message' => $message,
        ]);

    }
}
