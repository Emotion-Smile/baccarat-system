<?php

namespace App\Kravanh\Domain\Game\Actions;

use App\Kravanh\Domain\Game\Dto\GameTableConditionData;
use App\Kravanh\Domain\Game\Models\GameTableCondition;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class GameTableConditionGetAction
{
    public function __invoke(
        int $gameId,
        int $gameTableId,
        int $userId
    ): GameTableConditionData {
        try {

            $gameTableCondition = GameTableCondition::query()
                ->where('game_id', $gameId)
                ->where('game_table_id', $gameTableId)
                ->where('user_id', $userId)->firstOrFail();

            return GameTableConditionData::fromDatabase($gameTableCondition);

        } catch (ModelNotFoundException) {
            return GameTableConditionData::default(
                gameId: $gameId,
                gameTableId: $gameTableId,
                userId: $userId
            );
        }
    }
}
