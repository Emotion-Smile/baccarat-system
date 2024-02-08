<?php

namespace App\Kravanh\Domain\Baccarat\Subscribers;

use App\Kravanh\Domain\Camera\Jobs\ChangePresetNumberJob;
use App\Kravanh\Domain\Baccarat\Events\BaccaratGameCreated;
use App\Kravanh\Domain\Game\Supports\GameName;
use OptimistDigital\NovaSettings\Models\Settings;

class ChangeCameraPresetNumberAfterGameClosedBetSubscriber
{
    protected string $gameName;
    protected int $delayInSeconds;
    protected int $presetNumber;

    public function __construct()
    {
        $this->gameName = GameName::Baccarat->value;
        $this->delayInSeconds = Settings::getValueForKey("{$this->gameName}_camera_delay_after_closed_bet") ?? 0;
        $this->presetNumber = Settings::getValueForKey("{$this->gameName}_camera_preset_number_after_closed_bet") ?? 1;
    }

    public function handle(BaccaratGameCreated $event): void
    {
        ray($this->delayInSeconds + ($event->payload->bettingInterval + 1));

        ChangePresetNumberJob::dispatch(
            $this->gameName,
            $event->payload->tableId,
            $this->presetNumber
        )
            ->delay(now()->addSeconds(
                $this->delayInSeconds + ($event->payload->bettingInterval + 1)
            ));
    }
}
