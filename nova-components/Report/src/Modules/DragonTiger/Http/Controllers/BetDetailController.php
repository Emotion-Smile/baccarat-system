<?php

namespace KravanhEco\Report\Modules\DragonTiger\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use KravanhEco\Report\Modules\DragonTiger\Actions\GetBetDetailAction;
use KravanhEco\Report\Modules\DragonTiger\Http\Requests\WinLoseRequest;
use KravanhEco\Report\Modules\DragonTiger\Http\Resources\BetDetailResource;

class BetDetailController
{
    public function __invoke(User $user, WinLoseRequest $request): JsonResponse
    {
        $date = $request->getDate();

        $previousUserId = $request->getDetailPreviousUserId($user);

        $items = (new GetBetDetailAction)(
            user: $user,
            date: $date 
        );  

        return asJson([
            'previousUserId' => $previousUserId, 
            'items' => $items->through(
                fn ($item) => BetDetailResource::make($item)
            )
        ]);
    }
}
