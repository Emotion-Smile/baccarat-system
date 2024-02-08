<?php

namespace App\Kravanh\Domain\Integration\Http\Controllers\Api;

use App\Kravanh\Domain\Integration\Actions\Api\ProcessPayoutMemberBalanceAction;
use App\Kravanh\Domain\Integration\Http\Requests\Api\PayoutRequest;
use App\Kravanh\Domain\User\Models\Member;
use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

class PayoutController 
{
    public function __invoke(
        PayoutRequest $request
    ): JsonResponse
    {
        try {
            $user = $request->user();

            return ProcessPayoutMemberBalanceAction::make(
                member: Member::find($user->id),
                game: $request->game,
                amount: $request->amount,
                meta: $request->meta
            );
        } catch ( 
            Throwable
            | Exception $exception
        ) {
            return redirectError(__($exception->getMessage()));
        }
    }
}