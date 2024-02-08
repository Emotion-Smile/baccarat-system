<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerPayoutDeposited;

final class DragonTigerGameGetDepositedPayoutTicketIdsAction
{
    public function __invoke(int $dragonTigerGameId): array
    {
        return DragonTigerPayoutDeposited::query()
            ->where('dragon_tiger_game_id', $dragonTigerGameId)
            ->pluck('ticket_ids')
            ->toArray();
    }
}
