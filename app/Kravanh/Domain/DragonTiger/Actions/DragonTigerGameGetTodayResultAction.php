<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;

final class DragonTigerGameGetTodayResultAction
{
    public function __invoke(
        int $gameTableId,
        int $roundNumber = 0
    ) {
        return DragonTigerGame::query()
            ->select(['id', 'winner', 'number', 'round'])
            ->where('game_table_id', $gameTableId)
            ->when($roundNumber > 0, fn ($query) => $query->where('round', $roundNumber))
            ->excludeLiveGame()
            ->whereDate('created_at', today()->toDateString())
            ->get();
    }
}
