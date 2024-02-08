<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratPayoutDeposited;

final class BaccaratRecordPayoutDepositedAction
{
    public function __invoke(
        int $dragonTigerGameId,
        int $memberId,
        int $transactionId,
        int|float $amount,
        string $ticketIds,
    ): BaccaratPayoutDeposited {

        return BaccaratPayoutDeposited::create([
            'dragon_tiger_game_id' => $dragonTigerGameId,
            'member_id' => $memberId,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'ticket_ids' => $ticketIds,
        ]);

    }
}
