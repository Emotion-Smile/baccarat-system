<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class BaccaratGameGetTicketsBetOnPlayerAndBankerForTieResultAction
{
    public function __invoke(BaccaratGame $game): Collection
    {
        if (!$game->isTie()) {
            return Collection::make();
        }

        return BaccaratTicket::query()
            ->onlyBetOnPlayerAndBankerTickets(game: $game)
            ->with('member:id,name,currency')
            ->groupBy('user_id')
            ->get([
                'user_id',
                DB::raw("(SUM(amount)/2) as payout"),
                DB::raw("GROUP_CONCAT(id ORDER BY id ASC) as ids")
            ]);
    }

}
