<?php

namespace App\Kravanh\Domain\Integration\Actions\Api;

use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\LockHelper;
use Bavix\Wallet\Exceptions\AmountInvalid;
use Bavix\Wallet\Exceptions\BalanceIsEmpty;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Exception;
use Throwable;

class WithdrawMemberBalanceAction
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
            // if the time executes take longer than the waiting time it will throw the exception LockTimeoutException.
            $lock->block(config('balance.waiting_time_in_sec'));

            $transaction = $member->withdraw($amount);

            $transaction->meta = (new GenerateBettingMetaAction)(
                member: $member,
                game: $game,
                amount: $amount,
                meta: $meta 
            );

            $transaction->saveQuietly();

            $member->notifyRefreshBalance(priceFormat($member->getCurrentBalance()));
        } catch (
            AmountInvalid
            | BalanceIsEmpty
            | InsufficientFunds
            | Throwable
            | Exception $exception
        ) {
            throw $exception;
        } finally {
            $lock->release();
        }
    }
} 