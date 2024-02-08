<?php

namespace App\Kravanh\Domain\Match\Events;


use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class PayoutDeposited implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public bool $afterCommit = true;

    public function __construct(public int $environmentId, public int $memberId, public int|float|string $balance)
    {
    }

    public function broadcastOn(): Channel
    {
        return new Channel('match.' . $this->environmentId);
    }

    public function broadcastAs(): string
    {
        return 'payout.deposited.' . $this->memberId;
    }
}
