<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameNoLiveGameException;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;

final class BaccaratGameGetLiveGameIdByTableAction
{
    /**
     * @throws BaccaratGameNoLiveGameException
     */
    public function __invoke(int $gameTableId): int
    {
        $id = BaccaratGame::query()
            ->where('game_table_id', $gameTableId)
            ->whereLiveGame()
            ->value('id');

        if (is_null($id)) {
            throw new BaccaratGameNoLiveGameException();
        }

        return $id;
    }
}
