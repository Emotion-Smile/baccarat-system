<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

final class BaccaratGameGetNextNumberInRoundAction
{
    public function __invoke(
        int $gameTableId,
        int $round
    ): int {
        return (new BaccaratGameGetLastNumberAction())(
            gameTableId: $gameTableId,
            round: $round
        ) + 1;
    }
}
