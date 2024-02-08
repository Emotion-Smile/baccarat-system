<?php

namespace App\Kravanh\Domain\DragonTiger\App\Trader\Controllers\Api;

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetLiveGameIdByTableAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameSubmitCancelResultManagerAction;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameNoLiveGameException;
use App\Kravanh\Domain\DragonTiger\Support\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

final class DragonTigerGameSubmitCancelResultController
{
    /**
     * Submits the results of a Dragon Tiger game.
     *
     * @throws Throwable
     */
    public function __invoke(Request $request): JsonResponse
    {

        try {

            $user = $request->user();
            $dragonTigerGameId = $this->getLiveGameIdByTable($user->getGameTableId());

            app(DragonTigerGameSubmitCancelResultManagerAction::class)(
                dragonTigerGameId: $dragonTigerGameId,
                userId: $user->id
            );

            return ApiResponse::ok(
                message: 'The result was submitted successfully.'
            );

        } catch (Exception $e) {
            return ApiResponse::failed(
                message: __($e->getMessage())
            );
        }

    }

    /**
     * @throws DragonTigerGameNoLiveGameException
     */
    protected function getLiveGameIdByTable(int $gameTableId): int
    {
        return (new DragonTigerGameGetLiveGameIdByTableAction())(gameTableId: $gameTableId);
    }
}
