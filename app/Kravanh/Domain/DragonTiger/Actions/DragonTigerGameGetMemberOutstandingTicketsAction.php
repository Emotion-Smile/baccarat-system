<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameMemberOutstandingTicketData;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameNoLiveGameException;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use Illuminate\Support\Collection;

final class DragonTigerGameGetMemberOutstandingTicketsAction
{
    private Collection $tickets;

    public function __construct(
        private readonly int $userId,
        private readonly int $gameTableId,
    )
    {
        $this->tickets = $this->getOutstandingTickets();
    }

    public function tickets(): Collection
    {
        return $this->tickets->map(
            fn($ticket) => DragonTigerGameMemberOutstandingTicketData::from($ticket)
        )->values();
    }

    public function rawTickets(): Collection
    {
        return $this->tickets;
    }

    private function getOutstandingTickets()
    {

        try {

            $dragonTigerGameId = app(DragonTigerGameGetLiveGameIdByTableAction::class)(gameTableId: $this->gameTableId);

            return DragonTigerTicket::query()
                ->with(['game:id,round,number', 'user:id,currency'])
                ->where('user_id', $this->userId)
                ->where('dragon_tiger_game_id', $dragonTigerGameId)
                ->orderByDesc('id')
                ->get([
                    'payout_rate',
                    'dragon_tiger_tickets.id',
                    'dragon_tiger_tickets.bet_on',
                    'dragon_tiger_tickets.bet_type',
                    'dragon_tiger_tickets.amount',
                    'dragon_tiger_tickets.status',
                    'dragon_tiger_tickets.created_at',
                    'dragon_tiger_tickets.user_id',
                    'dragon_tiger_tickets.dragon_tiger_game_id'
                ]);

        } catch (DragonTigerGameNoLiveGameException) {
            return Collection::make();
        }


    }
}
