<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;

final class BaccaratGameGetMemberTotalBetAmountAction
{
    public function __invoke(int $gameId, int $userId): int
    {
        return (int)BaccaratTicket::query()
            ->where('dragon_tiger_game_id', $gameId)
            ->where('user_id', $userId)
            ->sum('amount');
    }
}
