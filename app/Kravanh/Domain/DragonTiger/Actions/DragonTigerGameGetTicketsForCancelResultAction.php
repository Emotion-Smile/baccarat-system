<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class DragonTigerGameGetTicketsForCancelResultAction
{
    public function __invoke(int $dragonTigerGameId): Collection
    {
        return DragonTigerTicket::query()
            ->with('member:id,name,currency')
            ->where('dragon_tiger_game_id', $dragonTigerGameId)
            ->accepted()
            ->groupBy('user_id')
            ->get([
                'user_id',
                DB::raw('SUM(amount) as payout'),
                DB::raw('GROUP_CONCAT(id ORDER BY id ASC) as ids'),
            ]);
    }
}
