<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use App\Kravanh\Domain\DragonTiger\Support\DateFilter;
use Illuminate\Database\Eloquent\Collection;

final class DragonTigerGameGetMemberTicketsAction
{
    public function __invoke(
        int        $userId,
        int        $gameTableId = 0,
        DateFilter $filterMode = DateFilter::Today,
    ): Collection
    {
        return DragonTigerTicket::query()
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
