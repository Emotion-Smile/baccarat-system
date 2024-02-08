<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use Illuminate\Support\Facades\Date;

final class BaccaratGameRoundNumberIsDuplicatedAction
{
    public function __invoke(int $gameTableId, int $round): bool
    {

        return (bool)BaccaratGame::query()
            ->where('game_table_id', $gameTableId)
            ->where('round', $round)
            ->whereDate('created_at', Date::today()->toDateString())
            ->count();
    }
}
