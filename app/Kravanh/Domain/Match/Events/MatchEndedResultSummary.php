<?php

namespace App\Kravanh\Domain\Match\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class MatchEndedResultSummary implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public array $match)
    {
    }

    public function broadcastOn(): Channel
    {

        return new Channel('match.' . $this->match['environment_id'] . '.' . $this->match['group_id']);
    }

    public function broadcastAs(): string
    {
        return 'endedResultSummary';
    }
}
