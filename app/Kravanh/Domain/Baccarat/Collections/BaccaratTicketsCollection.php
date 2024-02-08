<?php

namespace App\Kravanh\Domain\Baccarat\Collections;

use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameMemberTicketData;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use Illuminate\Database\Eloquent\Collection;


class BaccaratTicketsCollection extends Collection
{
    public function toOutstanding(): array
    {
        return $this->map(fn($ticket) => $ticket->id)->all();
    }

    public function toReport(): array
    {
        return $this
            ->map(fn(BaccaratTicket $ticket) => BaccaratGameMemberTicketData::from($ticket))
            ->all();
    }

}
