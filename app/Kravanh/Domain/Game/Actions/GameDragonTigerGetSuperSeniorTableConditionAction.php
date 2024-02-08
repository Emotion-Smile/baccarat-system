<?php

namespace App\Kravanh\Domain\Game\Actions;

use App\Kravanh\Domain\Game\Dto\GameTableConditionData;

final class GameDragonTigerGetSuperSeniorTableConditionAction
{
    public function __invoke(
        int $userId,
        int $tableId = null,
    ): GameTableConditionData {

        $superSeniorId = app(UserGetSuperSeniorIdAction::class)($userId);

        return app(GameDragonTigerGetTableConditionAction::class)(
            userId: $superSeniorId,
            tableId: $tableId
        );

    }
}
