<?php

namespace App\Kravanh\Domain\User\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RefreshTotalBet implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int    $environmentId,
        public int    $userId,
        public string $wala,
        public string $meron)
    {
    }

    public function broadcastOn(): Channel
    {
        return new Channel('user.' . $this->environmentId);
    }

    public function broadcastAs(): string
    {
        return 'totalBet.refresh.' . $this->userId;
    }

}
