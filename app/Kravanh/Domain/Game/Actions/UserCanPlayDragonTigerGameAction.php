<?php

namespace App\Kravanh\Domain\Game\Actions;

use App\Kravanh\Domain\Game\Models\GameTableCondition;

final class UserCanPlayDragonTigerGameAction
{
    public function __invoke(int $userId)
    {
        return GameTableCondition::select(['is_allowed'])
            ->where('game_id', app(GameGetDragonTigerGameIdAction::class)())
            ->where('user_id', $userId)
            ->first()?->is_allowed ?? false;
    }
}
