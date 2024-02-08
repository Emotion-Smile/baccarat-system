<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use Illuminate\Database\Eloquent\Builder;

final class BaccaratGameHasLiveGameAction
{
    public function __invoke(int|array $gameTableId): bool
    {
        return (bool)BaccaratGame::query()
            ->when(is_array($gameTableId),
                fn(Builder $query) => $query->whereIn('game_table_id', $gameTableId),
                fn(Builder $query) => $query->where('game_table_id', $gameTableId))
            ->whereLiveGame()->count();
    }
}
