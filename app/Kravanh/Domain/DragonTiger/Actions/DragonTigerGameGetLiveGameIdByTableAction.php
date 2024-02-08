<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameNoLiveGameException;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;

final class DragonTigerGameGetLiveGameIdByTableAction
{
    /**
     * @throws DragonTigerGameNoLiveGameException
     */
    public function __invoke(int $gameTableId): int
    {
        $id = DragonTigerGame::query()
            ->where('game_table_id', $gameTableId)
            ->whereLiveGame()
            ->value('id');

        if (is_null($id)) {
            throw new DragonTigerGameNoLiveGameException();
        }

        return $id;
    }
}
