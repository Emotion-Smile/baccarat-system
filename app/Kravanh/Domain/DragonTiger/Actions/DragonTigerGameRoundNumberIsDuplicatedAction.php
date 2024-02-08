<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use Illuminate\Support\Facades\Date;

final class DragonTigerGameRoundNumberIsDuplicatedAction
{
    public function __invoke(int $gameTableId, int $round): bool
    {

        return (bool)DragonTigerGame::query()
            ->where('game_table_id', $gameTableId)
            ->where('round', $round)
            ->whereDate('created_at', Date::today()->toDateString())
            ->count();
    }
}
