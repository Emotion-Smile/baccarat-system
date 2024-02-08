<?php

namespace App\Kravanh\Domain\Match\Jobs;

use App\Kravanh\Support\External\Trader\As88Trader;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class AdjustPayoutJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(public array $payload)
    {

    }

    public function handle()
    {
        As88Trader::action()->adjustPayout($this->payload);
    }
}
