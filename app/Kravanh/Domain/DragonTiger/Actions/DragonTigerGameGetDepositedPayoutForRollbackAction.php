<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerPayoutDeposited;
use Illuminate\Database\Eloquent\Collection;

final class DragonTigerGameGetDepositedPayoutForRollbackAction
{
    public function __invoke(int $dragonTigerGameId): Collection
    {
        return DragonTigerPayoutDeposited::query()
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
