<?php

namespace App\Kravanh\Domain\Match\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class TraderMatchSummary implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public bool $afterCommit = true;

    public function __construct(public array $match)
    {
    }

    public function broadcastOn(): Channel
    {
        return new Channel('match.trader.' . $this->match['environment_id'] . '.' . $this->match['group_id']);
    }

    public function broadcastAs(): string
    {
        return 'summary';
    }
}
