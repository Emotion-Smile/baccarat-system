<?php

namespace App\Kravanh\Domain\Camera\Jobs;

use App\Kravanh\Domain\Camera\Events\ChangePresetNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class ChangePresetNumberJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        protected string $gameName,
        protected int $tableId,
        protected int $presetNumber,
    )
    {}

    public function handle()
    {
        ray("Change preset number to {$this->presetNumber}");
        
        ChangePresetNumber::dispatch($this->gameName, $this->tableId, $this->presetNumber);
    }
}
