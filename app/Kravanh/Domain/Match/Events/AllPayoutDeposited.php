<?php

namespace App\Kravanh\Domain\Match\Events;


use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class AllPayoutDeposited implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public bool $afterCommit = true;

    public function __construct(public int $environmentId, public int $groupId, public array $balances)
    {
    }

    public function broadcastOn(): Channel
    {
        return new Channel('match.' . $this->environmentId . '.' . $this->groupId);
    }

    public function broadcastAs(): string
    {
        return 'payout.deposited.all';
    }
}
