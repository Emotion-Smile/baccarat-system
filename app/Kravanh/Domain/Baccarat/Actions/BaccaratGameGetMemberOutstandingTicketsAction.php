<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameMemberOutstandingTicketData;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameNoLiveGameException;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use Illuminate\Support\Collection;

final class BaccaratGameGetMemberOutstandingTicketsAction
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
            fn($ticket) => BaccaratGameMemberOutstandingTicketData::from($ticket)
        )->values();
    }

    public function rawTickets(): Collection
    {
        return $this->tickets;
    }

    private function getOutstandingTickets()
    {

        try {

            $BaccaratGameId = app(BaccaratGameGetLiveGameIdByTableAction::class)(gameTableId: $this->gameTableId);

            return BaccaratTicket::query()
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

        } catch (BaccaratGameNoLiveGameException) {
            return Collection::make();
        }


    }
}
