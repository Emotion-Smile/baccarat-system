<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\Baccarat\Support\DateFilter;
use Illuminate\Database\Eloquent\Collection;

final class BaccaratGameGetMemberTicketsAction
{
    public function __invoke(
        int        $userId,
        int        $gameTableId = 0,
        DateFilter $filterMode = DateFilter::Today,
    ): Collection
    {
        return BaccaratTicket::query()
            ->with([
                'game:id,round,number,winner,dragon_color,dragon_range,tiger_color,tiger_range',
                'user:id,currency',
                'gameTable:id,label'])
            ->where('user_id', $userId)
            ->whereGameTable($gameTableId)
            ->excludeOutstandingTickets()
            ->filterBy($filterMode)
            ->orderByDesc('id')
            ->get([
                'dragon_tiger_tickets.game_table_id',
                'dragon_tiger_tickets.id',
                'dragon_tiger_tickets.bet_on',
                'dragon_tiger_tickets.bet_type',
                'dragon_tiger_tickets.amount',
                'dragon_tiger_tickets.payout_rate',
                'dragon_tiger_tickets.payout',
                'dragon_tiger_tickets.status',
                'dragon_tiger_tickets.created_at',
                'dragon_tiger_tickets.user_id',
                'dragon_tiger_tickets.dragon_tiger_game_id'
            ]);
    }

}
