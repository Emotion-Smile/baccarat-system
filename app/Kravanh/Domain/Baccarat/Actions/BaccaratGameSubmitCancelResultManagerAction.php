<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameStateData;
use App\Kravanh\Domain\Baccarat\Events\BaccaratGameResultSubmitted;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;

final class BaccaratGameSubmitCancelResultManagerAction
{
    public function __invoke(
        int $dragonTigerGameId,
        int $userId): bool
    {

        $resultSubmitted = app(BaccaratGameSubmitCancelResultAction::class)(
            dragonTigerGameId: $dragonTigerGameId,
            userId: $userId
        );

        if (! $resultSubmitted) {
            return false;
        }

        BaccaratGameResultSubmitted::dispatchWithPayload(
            payload: BaccaratGameStateData::from(
                game: BaccaratGame::find($dragonTigerGameId)
            )
        );

        app(BaccaratPayoutProcessingManagerAction::class)(
            dragonTigerGameId: $dragonTigerGameId
        );

        return true;
    }
}
