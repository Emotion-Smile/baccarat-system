<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class DragonTigerGameGetTicketsBetOnDragonAndTigerForTieResultAction
{
    public function __invoke(DragonTigerGame $game): Collection
    {
        if (!$game->isTie()) {
            return Collection::make();
        }

        return DragonTigerTicket::query()
            ->onlyBetOnDragonAndTigerTickets(game: $game)
            ->with('member:id,name,currency')
            ->groupBy('user_id')
            ->get([
                'user_id',
                DB::raw("(SUM(amount)/2) as payout"),
                DB::raw("GROUP_CONCAT(id ORDER BY id ASC) as ids")
            ]);
    }

}
