<?php

namespace App\Kravanh\Domain\Camera\Events;

use App\Kravanh\Domain\Camera\Support\BroadcastAsEvent;
use App\Kravanh\Domain\Camera\Support\BroadcastOnChanel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class ChangePresetNumber implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        protected readonly string $gameName,
        protected readonly int $tableId,
        public readonly int $presetNumber
    ) 
    {}

    public function broadcastOn(): Channel
    {
        return new Channel(BroadcastOnChanel::table(
            gameName: $this->gameName, 
            tableId: $this->presetNumber
        ));
    }

    public function broadcastAs(): string
    {
        return BroadcastAsEvent::ChangedPresetNumber;
    }
}
