<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratPayoutDeposited;
use Illuminate\Database\Eloquent\Collection;

final class BaccaratGameGetDepositedPayoutForRollbackAction
{
    public function __invoke(int $dragonTigerGameId): Collection
    {
        return BaccaratPayoutDeposited::query()
            ->with('member:id,currency')
            ->where('dragon_tiger_game_id', $dragonTigerGameId)
            ->where('rollback_transaction_id', 0)
            ->get([
                'id',
                'member_id',
                'amount',
                'dragon_tiger_game_id',
            ]);
    }
}
