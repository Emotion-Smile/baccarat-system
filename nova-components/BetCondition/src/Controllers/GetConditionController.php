<?php

namespace Kravanh\BetCondition\Controllers;

use App\Kravanh\Domain\BetCondition\Actions\GetBetConditionAction;

final class GetConditionController
{
    public function __invoke(int $groupId, int $memberId, int $parentId)
    {
        return (new GetBetConditionAction())($groupId, $memberId, $parentId);
    }
}
