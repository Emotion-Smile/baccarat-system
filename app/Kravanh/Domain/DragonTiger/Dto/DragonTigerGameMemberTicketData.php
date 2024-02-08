<?php

namespace App\Kravanh\Domain\DragonTiger\Dto;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;

final class DragonTigerGameMemberTicketData
{
    public readonly int $ticketId;
    public readonly int $dragonTigerGameId;
    public readonly int $gameTableId;
    public readonly string $gameNumber;
    public readonly string $gameMainResult;
    public readonly string $gameSubResult;
    public readonly string $table;
    public readonly string $betOn;
    public readonly string $betAmount;
    public readonly string $ticketResult;
    public readonly string $winLose;
    public readonly string $time;
    public readonly string $dateTime;
    public readonly string $status;

    public function __construct(
        private readonly DragonTigerTicket $ticket
    )
    {
        $ticketFormat = $this->ticket->format();

        $this->ticketId = $this->ticket->id;
        $this->gameTableId = $this->ticket->game_table_id;
        $this->dragonTigerGameId = $this->ticket->dragon_tiger_game_id;
        $this->gameMainResult = $this->ticket->game->mainResult();
        $this->gameSubResult = $this->ticket->game->subResult();
        $this->gameNumber = $this->ticket->gameNumber();
        $this->table = $this->ticket->tableName();
        $this->betOn = $ticketFormat->bet();
        $this->betAmount = $ticketFormat->amountFormat($this->ticket->amount);
        $this->ticketResult = $this->ticket->result();
        $this->time = $ticketFormat->time();
        $this->dateTime = $ticketFormat->dateTime();
        $this->winLose = $ticketFormat->winLose();

    }

    public static function from(DragonTigerTicket $ticket): DragonTigerGameMemberTicketData
    {
        return (new DragonTigerGameMemberTicketData($ticket));
    }
}
