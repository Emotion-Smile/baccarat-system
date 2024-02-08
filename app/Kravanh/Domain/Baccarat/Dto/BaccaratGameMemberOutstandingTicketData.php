<?php

namespace App\Kravanh\Domain\Baccarat\Dto;

use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\Baccarat\Support\Balance;

final class BaccaratGameMemberOutstandingTicketData
{
    public readonly int $ticketId;
    public readonly string $gameNumber;
    public readonly string $betOn;
    public readonly string $winLose;
    public readonly string $betTime;
    public readonly string $status;

    public function __construct(BaccaratTicket $ticket)
    {
        $this->ticketId = $ticket->id;
        $this->gameNumber = $ticket->game->gameNumber();
        $this->betOn = $ticket->format()->bet();
        $this->winLose = Balance::format($ticket->amount, $ticket->user->currency) . " * " . $ticket->payout_rate . ": ???";
        $this->status = $ticket->status;
        $this->betTime = $ticket->created_at->format(config('kravanh.time_format'));
    }

    public static function from(BaccaratTicket $ticket): BaccaratGameMemberOutstandingTicketData
    {
        return (new BaccaratGameMemberOutstandingTicketData($ticket));
    }

}
