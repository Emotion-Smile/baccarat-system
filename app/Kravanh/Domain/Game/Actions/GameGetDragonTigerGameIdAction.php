<?php

namespace App\Kravanh\Domain\Game\Actions;

use App\Kravanh\Domain\Game\Supports\GameName;
use Illuminate\Support\Facades\Cache;

final class GameGetDragonTigerGameIdAction
{
    public function __invoke(): int
    {
        return Cache::remember(
            GameName::DragonTiger->value,
            now()->addMinutes(10),
            function () {
                return app(GameGetByNameAction::class)(
                    gameName: GameName::DragonTiger,
                    columns: ['id']
                )->id;
            }
        );
    }
}
