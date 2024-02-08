<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;

final class BaccaratGameGetTodayResultAction
{
    public function __invoke(
        int $gameTableId,
        int $roundNumber = 0
    ) {
        return BaccaratGame::query()
            ->select(['id', 'winner', 'number', 'round'])
            ->where('game_table_id', $gameTableId)
            ->when($roundNumber > 0, fn ($query) => $query->where('round', $roundNumber))
            ->excludeLiveGame()
            ->whereDate('created_at', today()->toDateString())
            ->get();
    }
}
