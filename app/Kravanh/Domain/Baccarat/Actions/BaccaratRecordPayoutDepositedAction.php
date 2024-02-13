<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratPayoutDeposited;

final class BaccaratRecordPayoutDepositedAction
{
    public function __invoke(
        int $baccaratGameId,
        int $memberId,
        int $transactionId,
        int|float $amount,
        string $ticketIds,
    ): BaccaratPayoutDeposited {

        return BaccaratPayoutDeposited::create([
            'baccarat_game_id' => $baccaratGameId,
            'member_id' => $memberId,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'ticket_ids' => $ticketIds,
        ]);

    }
}
