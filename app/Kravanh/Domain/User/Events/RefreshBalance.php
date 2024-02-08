<?php

namespace App\Kravanh\Domain\User\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RefreshBalance implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int    $environmentId,
        public int    $userId,
        public string $balance)
    {
    }

    public function broadcastOn(): Channel
    {
        return new Channel('user.' . $this->environmentId);
    }

    public function broadcastAs(): string
    {
        return 'balance.refresh.' . $this->userId;
    }

}
