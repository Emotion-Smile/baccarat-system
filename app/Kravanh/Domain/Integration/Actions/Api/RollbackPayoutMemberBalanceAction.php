<?php

namespace App\Kravanh\Domain\Integration\Actions\Api;

use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\LockHelper;
use Exception;

class RollbackPayoutMemberBalanceAction
{
    public function __invoke(
        Member $member,
        string $game,
        int $amount,
        array $meta
    ): void
    {
        $lock = LockHelper::lockWallet($member->id);

        try {
            $lock->block(config('balance.waiting_time_in_sec'));

            $transaction = $member->forceWithdraw($amount);

            $transaction->meta = (new GenerateRollbackPayoutMetaAction)(
                member: $member,
                game: $game,
                amount: $amount,
                meta: $meta 
            );

            $transaction->saveQuietly();
        } catch (Exception $exception) {
            throw $exception;
        } finally {
            $lock->release();
        }
    }
} 