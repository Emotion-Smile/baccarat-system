<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameStateData;
use App\Kravanh\Domain\DragonTiger\Events\DragonTigerGameResultSubmitted;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;

final class DragonTigerGameSubmitCancelResultManagerAction
{
    public function __invoke(
        int $dragonTigerGameId,
        int $userId): bool
    {

        $resultSubmitted = app(DragonTigerGameSubmitCancelResultAction::class)(
            dragonTigerGameId: $dragonTigerGameId,
            userId: $userId
        );

        if (! $resultSubmitted) {
            return false;
        }

        DragonTigerGameResultSubmitted::dispatchWithPayload(
            payload: DragonTigerGameStateData::from(
                game: DragonTigerGame::find($dragonTigerGameId)
            )
        );

        app(DragonTigerPayoutProcessingManagerAction::class)(
            dragonTigerGameId: $dragonTigerGameId
        );

        return true;
    }
}
