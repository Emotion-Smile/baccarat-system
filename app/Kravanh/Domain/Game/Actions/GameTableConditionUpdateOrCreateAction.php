<?php

namespace App\Kravanh\Domain\Game\Actions;

use App\Kravanh\Domain\Game\Dto\GameTableConditionData;
use App\Kravanh\Domain\Game\Models\GameTableCondition;

final class GameTableConditionUpdateOrCreateAction
{
    public function __invoke(
        GameTableConditionData $data
    ): GameTableCondition {
        return GameTableCondition::UpdateOrcreate(
            [
                'game_id' => $data->gameId,
                'game_table_id' => $data->gameTableId,
                'user_id' => $data->userId,
            ],
            [
                'user_type' => $data->userType,
                'is_allowed' => $data->isAllowed,
                'share_and_commission' => $data->shareAndCommission,
                'bet_condition' => $data->betCondition,
            ]);
    }
}
