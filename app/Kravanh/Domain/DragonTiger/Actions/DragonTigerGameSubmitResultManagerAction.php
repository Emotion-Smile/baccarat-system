<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameStateData;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameSubmitResultData;
use App\Kravanh\Domain\DragonTiger\Events\DragonTigerGameResultSubmitted;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameSubmitResultBetOpenException;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;

final class DragonTigerGameSubmitResultManagerAction
{
    /**
     * @throws DragonTigerGameSubmitResultBetOpenException
     */
    public function __invoke(DragonTigerGameSubmitResultData $data): bool
    {

        $resultSubmitted = app(DragonTigerGameSubmitResultAction::class)(data: $data);

        if (! $resultSubmitted) {
            return false;
        }

        DragonTigerGameResultSubmitted::dispatchWithPayload(
            payload: DragonTigerGameStateData::from(
                game: DragonTigerGame::find($data->dragonTigerGameId)
            )
        );

        return true;
    }
}
