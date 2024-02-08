<?php

namespace App\Kravanh\Domain\Baccarat\Collections;

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameBuildScoreboardAction;
use Illuminate\Database\Eloquent\Collection;

class BaccaratGameCollection extends Collection
{
    public function toScoreboard(): array
    {
        return BaccaratGameBuildScoreboardAction::from(
            gameResultHistory: $this->values()
        )->toScoreboard();
    }

    public function toScoreboardCount(): array
    {
        return BaccaratGameBuildScoreboardAction::from(
            gameResultHistory: $this->values()
        )->toCount();
    }
}
