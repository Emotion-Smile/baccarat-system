<?php

namespace App\Kravanh\Domain\Baccarat\Events;

use App\Kravanh\Domain\Baccarat\Support\BroadcastAsEvent;
use App\Kravanh\Domain\Baccarat\Support\BroadcastOnChanel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class BaccaratTicketCreated implements ShouldBroadcast
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
