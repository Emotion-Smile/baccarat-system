<?php

namespace App\Kravanh\Domain\Integration\Http\Controllers\Api;

use App\Kravanh\Domain\Integration\Actions\Api\RollbackPayoutMemberBalanceAction;
use App\Kravanh\Domain\Integration\Http\Requests\Api\RollbackPayoutRequest;
use App\Kravanh\Domain\User\Models\Member;
use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

class RollbackPayoutController
{
    public function __invoke(
        RollbackPayoutRequest $request
    ): JsonResponse
    {
        try {
            $user = $request->user();

            (new RollbackPayoutMemberBalanceAction)(
                member: Member::find($user->id),
                game: $request->game,
                amount: $request->amount,
                meta: $request->meta
            );

            return redirectSucceed('Rollback successfully.');
        } catch ( 
            Throwable
            | Exception $exception
        ) {
            return redirectError(__($exception->getMessage()));
        }
    }
}