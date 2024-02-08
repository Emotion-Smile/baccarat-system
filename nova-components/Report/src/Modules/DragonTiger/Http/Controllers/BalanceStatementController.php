<?php

namespace KravanhEco\Report\Modules\DragonTiger\Http\Controllers;

use App\Kravanh\Domain\User\Models\Member;
use Illuminate\Http\JsonResponse;
use KravanhEco\Report\Modules\DragonTiger\Actions\GetBalanceStatementAction;
use KravanhEco\Report\Modules\DragonTiger\Http\Requests\WinLoseRequest;
use KravanhEco\Report\Modules\DragonTiger\Http\Resources\BalanceStatementResource;
use KravanhEco\Report\Modules\DragonTiger\Support\Helpers\MoneyHelper;

class BalanceStatementController
{
    public function __invoke(WinLoseRequest $request, Member $member): JsonResponse
    {
        $date = $request->getDate();
        $previousUserId = $request->getDetailPreviousUserId($member);

        return asJson([
            'previousUserId' => $previousUserId,
            'balanceStatement' => (new GetBalanceStatementAction)($member, $date)->through(
                fn ($item) => BalanceStatementResource::make($item)
            ),
            'currentBalance' => MoneyHelper::fromKHR($member->balanceInt)->to($member->currency),
        ]);
    }
}
