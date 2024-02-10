<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class BaccaratGameGetTicketsForCancelResultAction
{
    public function __invoke(int $baccaratGameId): Collection
    {
        return BaccaratTicket::query()
            ->with('member:id,name,currency')
            ->where('baccarat_game_id', $baccaratGameId)
            ->accepted()
            ->groupBy('user_id')
            ->get([
                'user_id',
                DB::raw('SUM(amount) as payout'),
                DB::raw('GROUP_CONCAT(id ORDER BY id ASC) as ids'),
            ]);
    }
}
