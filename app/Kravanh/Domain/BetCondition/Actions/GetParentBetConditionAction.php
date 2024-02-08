<?php

namespace App\Kravanh\Domain\BetCondition\Actions;

use App\Kravanh\Domain\BetCondition\Models\BetCondition;
use App\Models\User;

final class GetParentBetConditionAction
{
    /**
     * @param int $groupId
     * @param int $parentId
     * @return object{minBetPerTicket: int,maxBetPerTicket: int,matchLimit: int, winLimitPerDay: int, force: bool}|null
     */

    public function __invoke(
        int $groupId,
        int $parentId,
    ): ?object
    {
        $parentCondition = BetCondition::getCondition($groupId, $parentId);

        if ($parentCondition) {
            return $parentCondition;
        }

        $defaultCondition = User::where('id', $parentId)->value('condition');

        return (object)[
            'minBetPerTicket' => (int)$defaultCondition['minimum_bet_per_ticket'],
            'maxBetPerTicket' => (int)$defaultCondition['maximum_bet_per_ticket'],
            'matchLimit' => (int)$defaultCondition['match_limit'],
            'winLimitPerDay' => (int)$defaultCondition['credit_limit'] ??= 0,
            'force' => false
        ];

    }


}
