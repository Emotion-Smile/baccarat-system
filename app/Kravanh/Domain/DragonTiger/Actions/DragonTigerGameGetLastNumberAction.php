<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;

final class DragonTigerGameGetLastNumberAction
{
    public function __invoke(
        int $gameTableId,
        int $round
    ): int {
        return DragonTigerGame::query()
            ->where('game_table_id', $gameTableId)
            ->where('round', $round)
            ->whereDate('started_at', now()->toDateString())
            ->orderByDesc('id')
            ->first(['number'])?->number ?? 0;
    }
}
