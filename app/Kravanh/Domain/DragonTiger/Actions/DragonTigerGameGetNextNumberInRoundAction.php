<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

final class DragonTigerGameGetNextNumberInRoundAction
{
    public function __invoke(
        int $gameTableId,
        int $round
    ): int {
        return (new DragonTigerGameGetLastNumberAction())(
            gameTableId: $gameTableId,
            round: $round
        ) + 1;
    }
}
