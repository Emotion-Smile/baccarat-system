<?php

namespace App\Kravanh\Domain\Match\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewBetCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public array $data)
    {
    }

    public function broadcastOn(): Channel
    {
        return new Channel('match.' . $this->data['environment_id'] . '.' . $this->data['group_id']);
    }

    public function broadcastAs(): string
    {
        return 'bet.created';
    }

}
