<?php

namespace App\Kravanh\Domain\Match\Jobs;

use App\Kravanh\Domain\Match\Actions\PayoutDepositorAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class BetPayoutJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        public array $payoutRecords,
        public array $match,
        public bool  $isWin)
    {
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function handle()
    {
        (new PayoutDepositorAction())(
            $this->payoutRecords,
            $this->match,
            $this->isWin
        );
    }
}
