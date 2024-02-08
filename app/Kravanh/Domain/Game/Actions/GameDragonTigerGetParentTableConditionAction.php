<?php

namespace App\Kravanh\Domain\Game\Actions;

use App\Kravanh\Domain\Game\Dto\GameTableConditionData;

final class GameDragonTigerGetParentTableConditionAction
{
    public function __invoke(
        int $userId,
        int $tableId = null,
    ): GameTableConditionData {

        $parentId = app(UserGetParentIdAction::class)($userId);

        return app(GameDragonTigerGetTableConditionAction::class)(
            userId: $parentId,
            tableId: $tableId
        );

    }
}
