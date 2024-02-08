<?php

namespace App\Kravanh\Application\Member\Controllers;

use App\Kravanh\Domain\User\Actions\MarkMessageAsReadAction;
use App\Kravanh\Domain\User\Models\Message;
use Illuminate\Http\JsonResponse;

class MarkMessageAsReadController
{
    public function __invoke(Message $message): JsonResponse
    {
        (new MarkMessageAsReadAction)($message);

        return asJson([
            'read' => true
        ]);
    }
}