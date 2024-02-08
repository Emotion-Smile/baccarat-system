<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Support\RoundMode;
use Illuminate\Support\Facades\Date;

final class BaccaratGameGetLastRoundAction
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
        return BaccaratGame::query()
            ->where('game_table_id', $gameTableId)
            ->whereDate('created_at', Date::today()->toDateString())
            ->orderByDesc('id')
            ->first(['round'])?->round;
    }
}
