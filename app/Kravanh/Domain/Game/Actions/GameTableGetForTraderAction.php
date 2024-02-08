<?php

namespace App\Kravanh\Domain\Game\Actions;

use App\Kravanh\Domain\Game\Models\GameTable;

final class GameTableGetForTraderAction
{
    public function __invoke(int $id): ?GameTable
    {
        return GameTable::query()
            ->select(['id', 'game_id', 'label', 'stream_url'])
            ->find($id);
    }
}
