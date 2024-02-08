<?php

namespace App\Kravanh\Domain\DragonTiger\Collections;

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameBuildScoreboardAction;
use Illuminate\Database\Eloquent\Collection;

class DragonTigerGameCollection extends Collection
{
    public function toScoreboard(): array
    {
        return DragonTigerGameBuildScoreboardAction::from(
            gameResultHistory: $this->values()
        )->toScoreboard();
    }

    public function toScoreboardCount(): array
    {
        return DragonTigerGameBuildScoreboardAction::from(
            gameResultHistory: $this->values()
        )->toCount();
    }
}
