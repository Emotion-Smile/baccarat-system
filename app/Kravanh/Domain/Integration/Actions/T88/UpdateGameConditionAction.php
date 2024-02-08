<?php

namespace App\Kravanh\Domain\Integration\Actions\T88;

use App\Kravanh\Domain\Integration\DataTransferObject\T88\UpdateGameConditionData;
use App\Kravanh\Domain\Integration\Models\T88GameCondition;

class UpdateGameConditionAction
{
    public function __invoke(
        UpdateGameConditionData $updateGameConditionData
    ): bool
    {
        return T88GameCondition::query()
            ->whereUserId($updateGameConditionData->userId)
            ->whereGameType($updateGameConditionData->gameType)
            ->first()
            ->update([
                'condition' => $updateGameConditionData->condition
            ]);
    }
}