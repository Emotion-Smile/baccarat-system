<?php

namespace App\Kravanh\Domain\BetCondition\Actions;

use App\Kravanh\Domain\BetCondition\Models\BetCondition;
use App\Models\User;

final class GetBetConditionAction
{
    /**
     * @param int $groupId
     * @param int $memberId
     * @param int $parentId
     * @return object{minBetPerTicket: int,maxBetPerTicket: int,matchLimit: int, winLimitPerDay: int, force: bool}
     */
    public function __invoke(
        int $groupId,
        int $memberId,
        int $parentId
    ): object
    {
        //Agent condition
        $agentCondition = BetCondition::getCondition($groupId, $parentId);

        if ($agentCondition?->force) {
            return $agentCondition;
        }

        //Member condition
        $memberCondition = BetCondition::getCondition($groupId, $memberId);

        if ($memberCondition) {
            return $memberCondition;
        }

        $defaultCondition = User::where('id', $memberId)->value('condition');

        return $this->makeReturn($defaultCondition, $agentCondition);

    }

    private function takeMinValue(int $defaultValue, int|null $agentValue)
    {
        if (!$agentValue) {
            return $defaultValue;
        }

        return min($defaultValue, $agentValue);
    }

    private function makeReturn($defaultCondition, $agentCondition): object
    {
        return (object)[
//            'minBetPerTicket' => $this->takeMinValue(
//                (int)$defaultCondition['minimum_bet_per_ticket'],
//                $agentCondition?->minBetPerTicket
//            ),
//            'maxBetPerTicket' =>
//                $this->takeMinValue(
//                    (int)$defaultCondition['maximum_bet_per_ticket'],
//                    $agentCondition?->maxBetPerTicket
//                ),
//            'matchLimit' => $this->takeMinValue(
//                (int)$defaultCondition['match_limit'],
//                $agentCondition?->matchLimit
//            ),
//            'winLimitPerDay' => $this->takeMinValue(
//                (int)$defaultCondition['credit_limit'] ??= 0,
//                null
//            ),
//            'force' => $agentCondition?->force ?? false
            'minBetPerTicket' => isset($defaultCondition['minimum_bet_per_ticket'])
                ? $this->takeMinValue((int)$defaultCondition['minimum_bet_per_ticket'], $agentCondition?->minBetPerTicket)
                : null,
            'maxBetPerTicket' => isset($defaultCondition['maximum_bet_per_ticket'])
                ? $this->takeMinValue((int)$defaultCondition['maximum_bet_per_ticket'], $agentCondition?->maxBetPerTicket)
                : null,
            'matchLimit' => isset($defaultCondition['match_limit'])
                ? $this->takeMinValue((int)$defaultCondition['match_limit'], $agentCondition?->matchLimit)
                : null,
            'winLimitPerDay' => $this->takeMinValue(
                (int)$defaultCondition['credit_limit'] ??= 0,
                null
            ),
            'force' => $agentCondition?->force ?? false
        ];

    }
}
