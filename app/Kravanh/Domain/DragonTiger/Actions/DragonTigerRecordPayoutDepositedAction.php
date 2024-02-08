<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerPayoutDeposited;

final class DragonTigerRecordPayoutDepositedAction
{
    public function __invoke(
        int $dragonTigerGameId,
        int $memberId,
        int $transactionId,
        int|float $amount,
        string $ticketIds,
    ): DragonTigerPayoutDeposited {

        return DragonTigerPayoutDeposited::create([
            'dragon_tiger_game_id' => $dragonTigerGameId,
            'member_id' => $memberId,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'ticket_ids' => $ticketIds,
        ]);

    }
}
