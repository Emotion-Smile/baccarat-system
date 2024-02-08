<?php

namespace App\Kravanh\Domain\Integration\Actions\T88;

use App\Kravanh\Domain\Integration\Models\T88PayoutDeposited;
use App\Kravanh\Domain\User\Models\Member;
use Bavix\Wallet\Models\Transaction;

class CreatePayoutDepositedAction
{
    public function __invoke(
        array $meta,
        Member $member,
        Transaction $transaction,
        string $depositor,
    ): T88PayoutDeposited
    {
        return T88PayoutDeposited::create([
            'member_id' => $member->id,
            'transaction_id' => $transaction->id,
            'ticket_id' => $meta['ticket_id'],
            'game_id' => (int) $meta['game_id'],
            'depositor' => $depositor
        ]);
    }

    public static function make(
        array $meta,
        Member $member,
        Transaction $transaction,
        string $depositor,
    ): T88PayoutDeposited 
    {
        return (new static)(
            meta: $meta,
            member: $member,
            transaction: $transaction,
            depositor: $depositor
        );
    }
}