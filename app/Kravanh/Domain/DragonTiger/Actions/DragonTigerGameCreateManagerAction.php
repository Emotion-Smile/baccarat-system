<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameCreateData;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameStateData;
use App\Kravanh\Domain\DragonTiger\Events\DragonTigerGameCreated;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameHasLiveGameException;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;

final class DragonTigerGameCreateManagerAction
{

    /**
     * @throws DragonTigerGameHasLiveGameException
     */
    public function __invoke(DragonTigerGameCreateData $data): DragonTigerGame
    {
        $game = (new DragonTigerGameCreateAction())($data);

        DragonTigerGameCreated::dispatchWithPayload(
            payload: DragonTigerGameStateData::from($game)
        );

        return $game;
    }
}
