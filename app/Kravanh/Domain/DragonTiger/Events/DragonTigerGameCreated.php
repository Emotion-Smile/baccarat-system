<?php

namespace App\Kravanh\Domain\DragonTiger\Events;

use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameStateData;
use App\Kravanh\Domain\DragonTiger\Support\BroadcastAsEvent;
use App\Kravanh\Domain\DragonTiger\Support\BroadcastOnChanel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

final class DragonTigerGameCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public readonly DragonTigerGameStateData $payload
    ) {
    }

    public function broadcastAs(): string
    {
        return BroadcastAsEvent::GameCreated;
    }

    public function broadcastOn(): Channel
    {
        return new Channel(BroadcastOnChanel::table(gameTableId: $this->payload->tableId));
    }

    public static function dispatchWithPayload(DragonTigerGameStateData $payload): void
    {
        DragonTigerGameCreated::dispatch($payload);
    }
}
