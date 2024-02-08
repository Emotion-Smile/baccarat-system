<?php

namespace App\Kravanh\Domain\Integration\Actions\AF88;

use App\Kravanh\Domain\Integration\DataTransferObject\AF88\UpdateGameConditionData;
use App\Kravanh\Domain\Integration\Models\Af88GameCondition;

class UpdateGameConditionAction
{
    public function __invoke(
        UpdateGameConditionData $updateGameConditionData
    ): bool
    {
        $af88GameCondition = Af88GameCondition::query()
            ->whereUserId($updateGameConditionData->userId)
            ->first();

        return $af88GameCondition->update([
                'condition' => [
                    ...$af88GameCondition->condition,
                    ...$updateGameConditionData->condition
                ]
            ]);
    }
}