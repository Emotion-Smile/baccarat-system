<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;

final class DragonTigerGameGetMemberTotalBetAmountAction
{
    public function __invoke(int $gameId, int $userId): int
    {
        return (int)DragonTigerTicket::query()
            ->where('dragon_tiger_game_id', $gameId)
            ->where('user_id', $userId)
            ->sum('amount');
    }
}
