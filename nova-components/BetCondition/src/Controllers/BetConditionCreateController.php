<?php

namespace Kravanh\BetCondition\Controllers;

use App\Kravanh\Domain\BetCondition\Actions\BetConditionCreateAction;
use App\Kravanh\Domain\BetCondition\Actions\GetParentBetConditionAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class BetConditionCreateController
{
    public function __invoke(Request $request): JsonResponse
    {

        $parentCondition = (new GetParentBetConditionAction())(
            groupId: $request->get('groupId'),
            parentId: $request->get('parentId')
        );

        $request->validate([
            'userId' => 'required|int',
            'minBetPerTicket' => "required|int|gte:$parentCondition->minBetPerTicket",
            'maxBetPerTicket' => "required|int|lte:$parentCondition->maxBetPerTicket",
            'matchLimit' => "required|int|lte:$parentCondition->matchLimit",
            'winLimitPerDay' => 'required|int',
            'force' => 'required|bool'
        ]);

        (new BetConditionCreateAction())(
            groupId: $request->get('groupId'),
            userId: $request->get('userId'),
            minBetPerTicket: $request->get('minBetPerTicket'),
            maxBetPerTicket: $request->get('maxBetPerTicket'),
            matchLimit: $request->get('matchLimit'),
            winLimitPerDay: $request->get('winLimitPerDay'),
            force: $request->get('force')
        );

        return response()->json([
            'message' => 'ok'
        ]);

    }
}
