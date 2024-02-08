<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;

final class DragonTigerGameGetLiveIdsAction
{
    public function __invoke(): array
    {
        return DragonTigerGame::query()
            ->select(['id'])
            ->whereLiveGame()
            ->get()
            ->toArray();
    }
}
