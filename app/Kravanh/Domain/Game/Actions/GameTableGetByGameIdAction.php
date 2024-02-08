<?php

namespace App\Kravanh\Domain\Game\Actions;

use App\Kravanh\Domain\Game\Models\GameTable;
use Illuminate\Database\Eloquent\Collection;

final class GameTableGetByGameIdAction
{
    public function __invoke(int $gameId): Collection
    {
        return GameTable::query()
            ->select(['id', 'game_id', 'label', 'stream_url'])
            ->where('active', true)
            ->where('game_id', $gameId)
            ->get();
    }
}
