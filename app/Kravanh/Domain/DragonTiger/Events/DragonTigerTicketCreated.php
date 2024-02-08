<?php

namespace App\Kravanh\Domain\DragonTiger\Events;

use App\Kravanh\Domain\DragonTiger\Support\BroadcastAsEvent;
use App\Kravanh\Domain\DragonTiger\Support\BroadcastOnChanel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class DragonTigerTicketCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        private readonly int $gameTableId,
        public array $payload
    ) {
    }

    public function broadcastAs(): string
    {
        return BroadcastAsEvent::TicketCreated;
    }

    public function broadcastOn(): Channel
    {
        return new Channel(BroadcastOnChanel::table(gameTableId: $this->gameTableId));
    }

    public static function payload(): array
    {
        return [

        ];
    }
}
