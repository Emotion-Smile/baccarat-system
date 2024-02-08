<?php

namespace App\Kravanh\Domain\Integration\Actions\T88;

use App\Kravanh\Domain\Integration\DataTransferObject\T88\CreateGameConditionData;
use App\Kravanh\Domain\Integration\Models\T88GameCondition;

class CreateGameConditionAction
{
    public function __invoke(
        CreateGameConditionData $createGameConditionData
    ): T88GameCondition
    {
        return T88GameCondition::create([
            'user_id' => $createGameConditionData->userId,
            'game_type' => $createGameConditionData->gameType,
            'condition' => $createGameConditionData->condition
        ]);
    }
}