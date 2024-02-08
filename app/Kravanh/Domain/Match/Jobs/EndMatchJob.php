<?php

namespace App\Kravanh\Domain\Match\Jobs;

use App\Kravanh\Support\External\Trader\F88Trader;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class EndMatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(public array $payload)
    {
    }

    public function handle(): void
    {
        if (F88Trader::getToken($this->payload['groupId'])) {
            F88Trader::make()->endMatch($this->payload);
        }
    }
}
