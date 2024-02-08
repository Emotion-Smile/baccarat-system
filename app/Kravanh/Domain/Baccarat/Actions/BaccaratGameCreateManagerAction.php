<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameCreateData;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameStateData;
use App\Kravanh\Domain\Baccarat\Events\BaccaratGameCreated;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameHasLiveGameException;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;

final class BaccaratGameCreateManagerAction
{

    /**
     * @throws BaccaratGameHasLiveGameException
     */
    public function __invoke(BaccaratGameCreateData $data): BaccaratGame
    {
        $game = (new BaccaratGameCreateAction())($data);

        BaccaratGameCreated::dispatchWithPayload(
            payload: BaccaratGameStateData::from($game)
        );

        return $game;
    }
}
