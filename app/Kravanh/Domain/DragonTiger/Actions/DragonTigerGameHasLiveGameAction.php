<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use Illuminate\Database\Eloquent\Builder;

final class DragonTigerGameHasLiveGameAction
{
    public function __invoke(int|array $gameTableId): bool
    {
        return (bool)DragonTigerGame::query()
            ->when(is_array($gameTableId),
                fn(Builder $query) => $query->whereIn('game_table_id', $gameTableId),
                fn(Builder $query) => $query->where('game_table_id', $gameTableId))
            ->whereLiveGame()->count();
    }
}
