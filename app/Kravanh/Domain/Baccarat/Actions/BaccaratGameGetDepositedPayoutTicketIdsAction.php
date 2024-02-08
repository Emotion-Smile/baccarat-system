<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratPayoutDeposited;

final class BaccaratGameGetDepositedPayoutTicketIdsAction
{
    public function __invoke(int $dragonTigerGameId): array
    {
        return BaccaratPayoutDeposited::query()
            ->where('dragon_tiger_game_id', $dragonTigerGameId)
            ->pluck('ticket_ids')
            ->toArray();
    }
}
