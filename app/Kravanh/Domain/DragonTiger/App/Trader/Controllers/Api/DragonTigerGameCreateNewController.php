<?php

namespace App\Kravanh\Domain\DragonTiger\App\Trader\Controllers\Api;

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameCreateManagerAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameCreateData;
use App\Kravanh\Domain\DragonTiger\Support\ApiResponse;
use App\Kravanh\Domain\DragonTiger\Support\RoundMode;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

final class DragonTigerGameCreateNewController
{
    public function __invoke(Request $request): JsonResponse
    {
        try {

            $game = (new DragonTigerGameCreateManagerAction())(
                data: DragonTigerGameCreateData::from(
                    user: $request->user(),
                    roundMode: $request->get('roundMode', RoundMode::LastRound),
                    betIntervalInSecond: $request->get('betIntervalInSecond', config('kravanh.dragon_tiger_betting_interval'))
                )
            );

            return ApiResponse::ok(
                message: 'New game created: #'.$game->gameNumber()
            );

        } catch (Exception|Throwable $e) {
            return ApiResponse::failed(
                message: __($e->getMessage())
            );
        }
    }
}
