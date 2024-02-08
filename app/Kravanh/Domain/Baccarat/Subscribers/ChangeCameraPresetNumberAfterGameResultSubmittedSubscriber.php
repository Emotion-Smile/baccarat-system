<?php

namespace App\Kravanh\Domain\Baccarat\Subscribers;

use App\Kravanh\Domain\Camera\Jobs\ChangePresetNumberJob;
use App\Kravanh\Domain\Baccarat\Events\BaccaratGameResultSubmitted;
use App\Kravanh\Domain\Game\Supports\GameName;
use OptimistDigital\NovaSettings\Models\Settings;

class ChangeCameraPresetNumberAfterGameResultSubmittedSubscriber
{
    protected string $gameName;
    protected int $delayInSeconds;
    protected int $presetNumber;

    public function __construct()
    {
        $this->gameName = GameName::Baccarat->value;
        $this->delayInSeconds = Settings::getValueForKey("{$this->gameName}_camera_delay_after_submitted_result") ?? 0;
        $this->presetNumber = Settings::getValueForKey("{$this->gameName}_camera_preset_number_after_submitted_result") ?? 1;
    }

    public function handle(BaccaratGameResultSubmitted $event): void
    {
        ray($this->delayInSeconds);

        ChangePresetNumberJob::dispatchIf(
            $event->payload->event === 'submit',
            $this->gameName,
            $event->payload->tableId,
            $this->presetNumber
        )
            ->delay(now()->addSeconds($this->delayInSeconds));
    }
}
