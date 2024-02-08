<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameNoLiveGameException;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;

final class DragonTigerGameGetLiveGameByTableAction
{
    /**
     * @throws DragonTigerGameNoLiveGameException
     */
    public function __invoke(int $gameTableId): DragonTigerGame
    {
        $game = DragonTigerGame::query()
            ->whereLiveGame()
            ->where('game_table_id', $gameTableId)
            ->first();

        if (is_null($game)) {
            throw new DragonTigerGameNoLiveGameException();
        }

        return $game;
    }
}
