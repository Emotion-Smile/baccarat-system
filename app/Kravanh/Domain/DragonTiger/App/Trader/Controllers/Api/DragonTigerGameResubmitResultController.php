<?php

namespace App\Kravanh\Domain\DragonTiger\App\Trader\Controllers\Api;

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameResubmitResultAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameSubmitResultData;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameNoLiveGameException;
use App\Kravanh\Domain\DragonTiger\Requests\DragonTigerGameResubmitResultRequest;
use App\Kravanh\Domain\DragonTiger\Support\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

final class DragonTigerGameResubmitResultController
{
    /**
     * Submits the results of a Dragon Tiger game.
     *
     * @throws DragonTigerGameNoLiveGameException
     * @throws Throwable
     */
    public function __invoke(DragonTigerGameResubmitResultRequest $request): JsonResponse
    {
        try {

            (new DragonTigerGameResubmitResultAction())(
                data: DragonTigerGameSubmitResultData::make(
                    user: $request->user(),
                    dragonTigerGameId: $request->getGameId(),
                    dragonResult: $request->getDragonResult(),
                    dragonType: $request->getDragonType(),
                    tigerResult: $request->getTigerResult(),
                    tigerType: $request->getTigerType()
                ));

            return ApiResponse::ok(
                message: 'The result was resubmitted successfully.'
            );

        } catch (Exception $e) {
            return ApiResponse::failed(
                message: __($e->getMessage())
            );
        }

    }
}
