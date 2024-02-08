<?php

namespace App\Kravanh\Domain\Match\Actions;


use App\Kravanh\Domain\Match\Models\PayoutDeposit;

class PayoutDepositedCreateAction
{
    public function __invoke(
        int    $matchId,
        int    $memberId,
        int    $transactionId,
        string $depositor = null
    ): void
    {
        PayoutDeposit::create([
            'match_id' => $matchId,
            'member_id' => $memberId,
            'transaction_id' => $transactionId,
            'depositor' => $depositor
        ]);

    }
}
