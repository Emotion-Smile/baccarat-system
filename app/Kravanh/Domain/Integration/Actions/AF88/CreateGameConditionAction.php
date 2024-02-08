<?php

namespace App\Kravanh\Domain\Integration\Actions\AF88;

use App\Kravanh\Domain\Integration\DataTransferObject\AF88\CreateGameConditionData;
use App\Kravanh\Domain\Integration\Models\Af88GameCondition;

class CreateGameConditionAction
{
    public function __invoke(
        CreateGameConditionData $createGameConditionData
    ): Af88GameCondition
    {
        return Af88GameCondition::create([
            'user_id' => $createGameConditionData->userId,
            'condition' => $createGameConditionData->condition
        ]);
    }
}