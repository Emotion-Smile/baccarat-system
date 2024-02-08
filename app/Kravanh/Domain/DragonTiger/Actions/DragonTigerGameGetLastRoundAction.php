<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Support\RoundMode;
use Illuminate\Support\Facades\Date;

final class DragonTigerGameGetLastRoundAction
{
    public function __invoke(
        int $gameTableId,
        string $roundMode = RoundMode::LastRound,
        int $betIntervalInSecond = 60): int
    {
        $lastRound = $this->getLastRound($gameTableId);

        if (is_null($lastRound)) {
            return 1;
        }

        if ($roundMode === RoundMode::NextRound) {
            $lastRound++;
        }

        return $lastRound;

    }

    private function getLastRound(int $gameTableId): ?int
    {
        return DragonTigerGame::query()
            ->where('game_table_id', $gameTableId)
            ->whereDate('created_at', Date::today()->toDateString())
            ->orderByDesc('id')
            ->first(['round'])?->round;
    }
}
