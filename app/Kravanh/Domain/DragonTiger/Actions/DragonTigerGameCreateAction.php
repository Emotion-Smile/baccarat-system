<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameCreateData;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameHasLiveGameException;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use Carbon\Carbon;

final class DragonTigerGameCreateAction
{
    /**
     * @throws DragonTigerGameHasLiveGameException
     */
    public function __invoke(DragonTigerGameCreateData $data): DragonTigerGame
    {

        if ((new DragonTigerGameHasLiveGameAction())($data->gameTableId)) {
            throw new DragonTigerGameHasLiveGameException();
        }

        return DragonTigerGame::create([
            'game_table_id' => $data->gameTableId,
            'user_id' => $data->userId,
            'round' => $data->round,
            'number' => $data->number,
            'started_at' => Carbon::now(),
            'closed_bet_at' => Carbon::now()->addSeconds($data->betIntervalInSecond),
        ]);
    }
}
