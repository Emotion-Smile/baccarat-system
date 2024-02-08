<?php

namespace App\Kravanh\Domain\Game\Actions;

use App\Kravanh\Domain\Game\Models\GameTable;

final class GameTableGetAction
{
    public function __invoke(int $id): ?GameTable
    {
        return GameTable::query()
            ->select(['id', 'game_id', 'label', 'stream_url'])
            ->where('active', true)
            ->find($id);
    }
}
