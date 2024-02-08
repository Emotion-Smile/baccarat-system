<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;

final class BaccaratGameGetLastNumberAction
{
    public function __invoke(
        int $gameTableId,
        int $round
    ): int {
        return BaccaratGame::query()
            ->where('game_table_id', $gameTableId)
            ->where('round', $round)
            ->whereDate('started_at', now()->toDateString())
            ->orderByDesc('id')
            ->first(['number'])?->number ?? 0;
    }
}
