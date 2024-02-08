<?php

namespace App\Kravanh\Domain\Match\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class MatchDepositPayoutCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public array $match)
    {
    }

    public function broadcastOn(): Channel
    {
        return new Channel('match.' . $this->match['envId'] . '.' . $this->match['groupId']);
    }

    public function broadcastAs(): string
    {
        return 'deposit.payout.completed';
    }
}
