<?php

namespace App\Kravanh\Domain\Integration\Actions\Api;

use App\Kravanh\Domain\Integration\Actions\Api\CreatePayoutDepositedAction;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\LockHelper;
use Exception;

class PayoutMemberBalanceAction
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

            $transaction = $member->deposit($amount);

            $transaction->meta = GeneratePayoutMetaAction::make(
                member: $member,
                game: $game,
                amount: $amount,
                meta: $meta
            );
            
            $transaction->save();

            CreatePayoutDepositedAction::make(
                game: $game,
                meta: $meta,
                member: $member,
                transaction: $transaction
            );
            
            $member->notifyRefreshBalance(priceFormat($member->getCurrentBalance()));
        } catch (Exception $exception) {
            throw $exception;
        } finally {
            $lock->release();
        }
    }

    public static function make(
        Member $member,
        string $game,
        int $amount,
        array $meta
    ): void 
    {
        (new static)(
            member: $member,
            game: $game,
            amount: $amount,
            meta: $meta
        );
    }
} 