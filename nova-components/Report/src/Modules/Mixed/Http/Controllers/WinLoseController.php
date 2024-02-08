<?php

namespace KravanhEco\Report\Modules\Mixed\Http\Controllers;

use Illuminate\Http\JsonResponse;
use KravanhEco\Report\Modules\Mixed\Actions\GetCockfightWinLoseAction;
use KravanhEco\Report\Modules\Mixed\Actions\GetDragonTigerWinLoseAction;
use KravanhEco\Report\Modules\Mixed\Actions\GetYukiWinLoseAction;
use KravanhEco\Report\Modules\Mixed\Actions\WinLoseBuilderAction;
use KravanhEco\Report\Modules\Mixed\Http\Requests\WinLoseRequest;
use KravanhEco\Report\Modules\Mixed\Http\Resources\TotalWinLoseResource;
use KravanhEco\Report\Modules\Mixed\Http\Resources\WinLoseItemResource;

class WinLoseController
{
    public function __invoke(WinLoseRequest $request): JsonResponse
    {
        $user = $request->getUser();
        $date = $request->getDate();
        
        $cockfight = GetCockfightWinLoseAction::make($request)->items();
        $dragonTiger = GetDragonTigerWinLoseAction::make($user, $date)->items();
        $yuki = GetYukiWinLoseAction::make($user, $date)->items();

        $mixed = WinLoseBuilderAction::from(
            items: collect()
                ->merge($cockfight)
                ->merge($dragonTiger)
                ->merge($yuki)
                ->groupBy('account')
                ->values()
        );
        
        return asJson([
            'previousUserId' => $request->getPreviousUserId($user),
            'items' => WinLoseItemResource::collection($mixed->items()),
            'total' => TotalWinLoseResource::make($mixed->total(), $user),
            'userType' => [
                'value' => $user->type->value,
                'text' => $user->type->description
            ],
        ]);
    }
}