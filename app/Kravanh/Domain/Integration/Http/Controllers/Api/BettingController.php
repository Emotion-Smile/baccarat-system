<?php

namespace App\Kravanh\Domain\Integration\Http\Controllers\Api;

use App\Kravanh\Domain\Integration\Actions\Api\WithdrawMemberBalanceAction;
use App\Kravanh\Domain\Integration\Http\Requests\Api\BettingRequest;
use App\Kravanh\Domain\User\Models\Member;
use Bavix\Wallet\Exceptions\AmountInvalid;
use Bavix\Wallet\Exceptions\BalanceIsEmpty;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

class BettingController
{
    public function __invoke(
        BettingRequest $request
    ): JsonResponse
    {
        try {
            $user = $request->user();

            (new WithdrawMemberBalanceAction)(
                member: Member::find($user->id),
                game: $request->game,
                amount: $request->amount,
                meta: $request->meta
            );

            return redirectSucceed('Betting successfully.');
        } catch ( 
            AmountInvalid
            | BalanceIsEmpty
            | InsufficientFunds
            | Throwable
            | Exception $exception
        ) {
            return redirectError(__($exception->getMessage()));
        }
    }
} 