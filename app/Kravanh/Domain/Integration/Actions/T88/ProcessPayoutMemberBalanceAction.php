<?php

namespace App\Kravanh\Domain\Integration\Actions\T88;

use App\Kravanh\Domain\Integration\Actions\Api\PayoutMemberBalanceAction;
use App\Kravanh\Domain\Integration\Models\T88PayoutDeposited;
use App\Kravanh\Domain\Integration\Supports\Helpers\LockStateHelper;
use App\Kravanh\Domain\User\Models\Member;
use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

class ProcessPayoutMemberBalanceAction
{
    public function __invoke(
        string $game,
        array $meta,
        int $amount,
        Member $member
    ): JsonResponse
    {
        try {
            return LockStateHelper::make(
                key: 'lock:integration:payout:' . $member->id . ':' . $game . ':' . $meta['ticket_id']
            )
                ->wrap(function (LockStateHelper $lockState) use ($member, $game, $amount, $meta) {
                    // if(T88PayoutDeposited::isPaid($member->id, (int) $meta['game_id'])) {
                    //     $lockState->release();

                    //     return redirectSucceed('Payout successfully.');
                    // }
                
                    PayoutMemberBalanceAction::make(
                        member: $member,
                        game: $game,
                        amount: $amount,
                        meta: $meta
                    );

                    $lockState->release();

                    return redirectSucceed('Payout successfully.');
                });
        } catch ( 
            Throwable
            | Exception $exception
        ) {
            return redirectError(__($exception->getMessage()));
        }
    }

    public static function make(
        string $game,
        array $meta,
        int $amount,
        Member $member
    )
    {
        return (new static)(
            game: $game,
            meta: $meta,
            amount: $amount,
            member: $member,
        );
    }
} 