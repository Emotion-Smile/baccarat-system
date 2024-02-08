<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class DragonTigerGameGetWinningTicketsAction
{
    public function __invoke(DragonTigerGame $game): Collection
    {
        return DragonTigerTicket::query()
            ->onlyWinningTickets(game: $game)
            ->with('member:id,name,currency')
            ->groupBy('user_id')
            ->get([
                'user_id',
                DB::raw('(SUM(amount) + SUM(payout)) as payout'),
                DB::raw('GROUP_CONCAT(id ORDER BY id ASC) as ids'),
            ]);
    }
}
