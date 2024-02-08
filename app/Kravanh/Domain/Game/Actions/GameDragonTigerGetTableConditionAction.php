<?php

namespace App\Kravanh\Domain\Game\Actions;

use App\Kravanh\Domain\Game\Dto\GameTableConditionData;

final class GameDragonTigerGetTableConditionAction
{
    public function __invoke(
        int $userId,
        int $tableId = null,
    ): GameTableConditionData {

        if ($userId === 0) {
            return GameTableConditionData::default(
                gameId: 0,
                gameTableId: 0,
                userId: 0
            );
        }

        $dragonTiger = app(GameDragonTigerGetAction::class)();

        return app(GameTableConditionGetAction::class)(
            gameId: $dragonTiger->id,
            gameTableId: $tableId ?? $dragonTiger->firstTableId(),
            userId: $userId
        );
    }
}
