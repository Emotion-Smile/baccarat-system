<?php

namespace App\Kravanh\Domain\Game\Actions;

use App\Kravanh\Domain\Game\Models\Game;
use App\Kravanh\Domain\Game\Supports\GameName;

final class GameDragonTigerGetAction
{
    public function __invoke(): Game
    {
        return app(GameGetByNameAction::class)(GameName::DragonTiger);
    }
}
