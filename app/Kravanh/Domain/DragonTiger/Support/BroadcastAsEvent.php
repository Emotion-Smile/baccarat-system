<?php

namespace App\Kravanh\Domain\DragonTiger\Support;

final class BroadcastAsEvent
{
    const TicketCreated = 'ticket.created';

    const GameCreated = 'game.created';

    const GameResultSubmitted = 'game.resultSubmitted';

    const CardScanned = 'card.scanned';
}
