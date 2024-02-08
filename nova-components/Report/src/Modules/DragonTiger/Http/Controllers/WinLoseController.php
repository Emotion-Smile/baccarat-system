<?php

namespace KravanhEco\Report\Modules\DragonTiger\Http\Controllers;

use Illuminate\Http\JsonResponse;
use KravanhEco\Report\Modules\DragonTiger\Actions\GetWinLoseAction;
use KravanhEco\Report\Modules\DragonTiger\Actions\WinLoseBuilderAction;
use KravanhEco\Report\Modules\DragonTiger\Http\Requests\WinLoseRequest;
use KravanhEco\Report\Modules\DragonTiger\Http\Resources\TotalWinLoseResource;
use KravanhEco\Report\Modules\DragonTiger\Http\Resources\WinLoseItemResource;

class WinLoseController
{
    public function __invoke(WinLoseRequest $request): JsonResponse
    {
        $user = $request->getUser();
        $date = $request->getDate();

        $previousUserId = $request->getPreviousUserId($user);

        $winLose = WinLoseBuilderAction::from(
            items: (new GetWinLoseAction)(
                user: $user,
                date: $date 
            )
        );

        return asJson([
            'previousUserId' => $previousUserId, 
            'items' => WinLoseItemResource::collection($winLose->items()),
            'total' => TotalWinLoseResource::make($winLose->total(), $user),
            'userType' => [
                'value' => $user->type->value,
                'text' => $user->type->description
            ],
        ]);
    }
}
