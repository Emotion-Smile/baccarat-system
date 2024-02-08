<?php

namespace App\Kravanh\Domain\Game\Actions;

use App\Kravanh\Domain\Game\Models\GameTable;

final class GameTableIsAvailableAction
{
    public function __invoke(int $gameTableId): bool
    {
        return (bool) GameTable::query()->where('id', $gameTableId)->value('active');
    }
}
