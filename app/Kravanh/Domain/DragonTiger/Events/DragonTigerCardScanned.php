<?php

namespace App\Kravanh\Domain\DragonTiger\Events;

use App\Kravanh\Domain\DragonTiger\Support\BroadcastAsEvent;
use App\Kravanh\Domain\DragonTiger\Support\BroadcastOnChanel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class DragonTigerCardScanned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        private readonly int $gameTableId,
        public string $card, //dragon, tiger
        public int $value, // 1-13
        public string $type // spade, heart, diamond, club
    ) {
    }

    public function broadcastAs(): string
    {
        return BroadcastAsEvent::CardScanned;
    }

    public function broadcastOn(): Channel
    {
        return new Channel(BroadcastOnChanel::table(gameTableId: $this->gameTableId));
    }
}
