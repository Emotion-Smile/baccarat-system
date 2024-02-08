<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameCreateData;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameHasLiveGameException;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use Carbon\Carbon;

final class BaccaratGameCreateAction
{
    /**
     * @throws BaccaratGameHasLiveGameException
     */
    public function __invoke(BaccaratGameCreateData $data): BaccaratGame
    {

        if ((new BaccaratGameHasLiveGameAction())($data->gameTableId)) {
            throw new BaccaratGameHasLiveGameException();
        }

        return BaccaratGame::create([
            'game_table_id' => $data->gameTableId,
            'user_id' => $data->userId,
            'round' => $data->round,
            'number' => $data->number,
            'started_at' => Carbon::now(),
            'closed_bet_at' => Carbon::now()->addSeconds($data->betIntervalInSecond),
        ]);
    }
}
