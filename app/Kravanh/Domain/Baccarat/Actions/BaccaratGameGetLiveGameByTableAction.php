<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameNoLiveGameException;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;

final class BaccaratGameGetLiveGameByTableAction
{
    /**
     * @throws BaccaratGameNoLiveGameException
     */
    public function __invoke(int $gameTableId): BaccaratGame
    {
        $game = BaccaratGame::query()
            ->whereLiveGame()
            ->where('game_table_id', $gameTableId)
            ->first();

        if (is_null($game)) {
            throw new BaccaratGameNoLiveGameException();
        }

        return $game;
    }
}
