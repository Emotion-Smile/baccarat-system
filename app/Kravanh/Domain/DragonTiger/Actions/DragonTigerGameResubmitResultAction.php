<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameStateData;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameSubmitResultData;
use App\Kravanh\Domain\DragonTiger\Events\DragonTigerGameResultSubmitted;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameResubmitResultOnLiveException;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use Throwable;

final class DragonTigerGameResubmitResultAction
{
    /**
     * @throws Throwable
     */
    public function __invoke(DragonTigerGameSubmitResultData $data): bool
    {

        $game = DragonTigerGame::find($data->dragonTigerGameId);

        if ($game->isLive()) {
            throw new DragonTigerGameResubmitResultOnLiveException();
        }

        $isSubmitted = DragonTigerGameSubmitResultAction::from(data: $data);

        if (! $isSubmitted) {
            return false;
        }

        $this->broadcastEventGameResultSubmit($data->dragonTigerGameId);

        $payouts = app(DragonTigerGameGetDepositedPayoutForRollbackAction::class)(
            dragonTigerGameId: $data->dragonTigerGameId
        );

        app(DragonTigerGameRollbackPayoutAction::class)(payouts: $payouts);

        app(DragonTigerPayoutProcessingManagerAction::class)(
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
