<?php

namespace App\Kravanh\Domain\Game\Actions;

use App\Kravanh\Domain\Game\Dto\GameTableConditionData;

final class GameDragonTigerGetCompanyTableConditionAction
{
    public function __invoke(
        int $tableId = null,
    ): GameTableConditionData {
        $game = app(GameDragonTigerGetAction::class)();

        return GameTableConditionData::default(
            gameId: $game->id,
            gameTableId: $game->firstTableId(),
            userId: 0
        )->setShare(100);
    }
}
