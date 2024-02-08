<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameStateData;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameSubmitResultData;
use App\Kravanh\Domain\Baccarat\Events\BaccaratGameResultSubmitted;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameResubmitResultOnLiveException;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use Throwable;

final class BaccaratGameResubmitResultAction
{
    /**
     * @throws Throwable
     */
    public function __invoke(BaccaratGameSubmitResultData $data): bool
    {

        $game = BaccaratGame::find($data->dragonTigerGameId);

        if ($game->isLive()) {
            throw new BaccaratGameResubmitResultOnLiveException();
        }

        $isSubmitted = BaccaratGameSubmitResultAction::from(data: $data);

        if (! $isSubmitted) {
            return false;
        }

        $this->broadcastEventGameResultSubmit($data->dragonTigerGameId);

        $payouts = app(BaccaratGameGetDepositedPayoutForRollbackAction::class)(
            dragonTigerGameId: $data->dragonTigerGameId
        );

        app(BaccaratGameRollbackPayoutAction::class)(payouts: $payouts);

        app(BaccaratPayoutProcessingManagerAction::class)(
            dragonTigerGameId: $data->dragonTigerGameId
        );

        return true;

    }

    private function broadcastEventGameResultSubmit(int $dragonTigerGameId): void
    {
        DragonTigerGameResultSubmitted::dispatchWithPayload(
            payload: DragonTigerGameStateData::from(
                game: DragonTigerGame::find($dragonTigerGameId),
                event: 'resubmit'
            )
        );
    }
}
