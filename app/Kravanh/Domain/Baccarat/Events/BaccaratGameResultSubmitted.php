<?php

namespace App\Kravanh\Domain\Baccarat\Events;

use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameStateData;
use App\Kravanh\Domain\Baccarat\Support\BroadcastAsEvent;
use App\Kravanh\Domain\Baccarat\Support\BroadcastOnChanel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

final class BaccaratGameResultSubmitted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public readonly BaccaratGameStateData $payload
    ) {
    }

    public function broadcastAs(): string
    {
        return BroadcastAsEvent::GameResultSubmitted;
    }

    public function broadcastOn(): Channel
    {
        return new Channel(BroadcastOnChanel::table(gameTableId: $this->payload->tableId));
    }

    public static function dispatchWithPayload(BaccaratGameStateData $payload): void
    {
        BaccaratGameResultSubmitted::dispatch($payload);
    }
}
