<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class BaccaratGameGetWinningTicketsAction
{
    public function __invoke(BaccaratGame $game): Collection
    {
        return BaccaratTicket::query()
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
