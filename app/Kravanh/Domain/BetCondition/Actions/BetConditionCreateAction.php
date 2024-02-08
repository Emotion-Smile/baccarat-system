<?php

namespace App\Kravanh\Domain\BetCondition\Actions;

use App\Kravanh\Domain\BetCondition\Models\BetCondition;

final class BetConditionCreateAction
{
    public function __invoke(
        int $groupId,
        int $userId,
        int $minBetPerTicket,
        int $maxBetPerTicket,
        int $matchLimit,
        int $winLimitPerDay,
        bool $force = false
    ): void {

        $condition = [
            'minBetPerTicket' => $minBetPerTicket,
            'maxBetPerTicket' => $maxBetPerTicket,
            'matchLimit' => $matchLimit,
            'winLimitPerDay' => $winLimitPerDay,
            'force' => $force,
        ];

        $userBetCondition = BetCondition::query()
            ->where('group_id', $groupId)
            ->where('user_id', $userId)
            ->first();

        if ($userBetCondition) {
            BetCondition::query()
                ->where('group_id', $groupId)
                ->where('user_id', $userId)
                ->update(['condition' => $condition]);
        } else {
            BetCondition::create([
                'group_id' => $groupId,
                'user_id' => $userId,
                'condition' => $condition,
            ]);
        }
    }
}
