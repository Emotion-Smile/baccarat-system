<?php

namespace App\Kravanh\Domain\DragonTiger\App\Trader\Controllers\Api;

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetLiveGameIdByTableAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameSubmitResultManagerAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerPayoutProcessingManagerAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameSubmitResultData;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameNoLiveGameException;
use App\Kravanh\Domain\DragonTiger\Requests\DragonTigerGameSubmitResultRequest;
use App\Kravanh\Domain\DragonTiger\Support\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

final class DragonTigerGameSubmitResultController
{
    /**
     * Submits the results of a Dragon Tiger game.
     *
     * @param  DragonTigerGameSubmitResultRequest  $request The Dragon Tiger game submit result request.
     *
     * @throws DragonTigerGameNoLiveGameException
     * @throws Throwable
     */
    public function __invoke(DragonTigerGameSubmitResultRequest $request): JsonResponse
    {
        try {
            $dragonTigerGameId = $this->getLiveGameIdByTable(
                gameTableId: $request->getGameTableId()
            );

            $this->submitResults(
                dragonTigerGameId: $dragonTigerGameId,
                request: $request
            );

            $this->processPayout(
                dragonTigerGameId: $dragonTigerGameId
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

    /**
     * @throws Throwable
     */
    protected function submitResults(
        int $dragonTigerGameId,
        DragonTigerGameSubmitResultRequest $request
    ): void {
        (new DragonTigerGameSubmitResultManagerAction())(
            data: DragonTigerGameSubmitResultData::make(
                user: $request->user(),
                dragonTigerGameId: $dragonTigerGameId,
                dragonResult: $request->getDragonResult(),
                dragonType: $request->getDragonType(),
                tigerResult: $request->getTigerResult(),
                tigerType: $request->getTigerType()
            ));
    }

    protected function processPayout(int $dragonTigerGameId): void
    {
        app(DragonTigerPayoutProcessingManagerAction::class)(dragonTigerGameId: $dragonTigerGameId);
    }
}
