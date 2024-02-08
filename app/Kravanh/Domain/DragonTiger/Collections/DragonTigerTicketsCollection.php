<?php

namespace App\Kravanh\Domain\DragonTiger\Collections;

use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameMemberTicketData;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use Illuminate\Database\Eloquent\Collection;


class DragonTigerTicketsCollection extends Collection
{
    public function toOutstanding(): array
    {
        return $this->map(fn($ticket) => $ticket->id)->all();
    }

    public function toReport(): array
    {
        return $this
            ->map(fn(DragonTigerTicket $ticket) => DragonTigerGameMemberTicketData::from($ticket))
            ->all();
    }

}
