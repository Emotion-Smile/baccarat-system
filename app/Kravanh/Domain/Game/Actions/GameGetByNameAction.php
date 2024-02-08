<?php

namespace App\Kravanh\Domain\Game\Actions;

use App\Kravanh\Domain\Game\Models\Game;
use App\Kravanh\Domain\Game\Supports\GameName;

final class GameGetByNameAction
{
    public function __invoke(
        GameName $gameName,
        array $columns = ['*']
    ): Game {
        return Game::whereName($gameName->value)->firstOrFail($columns);
    }
}
