<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class BaccaratGameGetTicketsForCancelResultAction
{
    public function __invoke(int $dragonTigerGameId): Collection
    {
        return BaccaratTicket::query()
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
