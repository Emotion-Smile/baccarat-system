<?php

namespace App\Kravanh\Domain\DragonTiger\Dto;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use App\Kravanh\Domain\DragonTiger\Support\Balance;

final class DragonTigerGameMemberOutstandingTicketData
{
    public readonly int $ticketId;
    public readonly string $gameNumber;
    public readonly string $betOn;
    public readonly string $winLose;
    public readonly string $betTime;
    public readonly string $status;

    public function __construct(DragonTigerTicket $ticket)
    {
        $this->ticketId = $ticket->id;
        $this->gameNumber = $ticket->game->gameNumber();
        $this->betOn = $ticket->format()->bet();
        $this->winLose = Balance::format($ticket->amount, $ticket->user->currency) . " * " . $ticket->payout_rate . ": ???";
        $this->status = $ticket->status;
        $this->betTime = $ticket->created_at->format(config('kravanh.time_format'));
    }

    public static function from(DragonTigerTicket $ticket): DragonTigerGameMemberOutstandingTicketData
    {
        return (new DragonTigerGameMemberOutstandingTicketData($ticket));
    }

}
