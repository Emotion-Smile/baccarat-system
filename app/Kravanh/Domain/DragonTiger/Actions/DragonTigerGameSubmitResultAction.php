<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameSubmitResultData;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameSubmitResultBetOpenException;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use Illuminate\Support\Facades\Date;


final class DragonTigerGameSubmitResultAction
{
    /**
     * @throws DragonTigerGameSubmitResultBetOpenException
     */
    public static function from(DragonTigerGameSubmitResultData $data): bool
    {
        return (new DragonTigerGameSubmitResultAction())($data);
    }

    /**
     * @throws DragonTigerGameSubmitResultBetOpenException
     */
    public function __invoke(DragonTigerGameSubmitResultData $data): bool
    {

        $game = DragonTigerGame::find($data->dragonTigerGameId);

        if (! $game->isBetClosed()) {
            throw new DragonTigerGameSubmitResultBetOpenException();
        }

        return (bool) DragonTigerGame::query()
            ->where('id', $data->dragonTigerGameId)
            ->update([
                'result_submitted_user_id' => $data->user->id,
                'dragon_result' => $data->dragonResult,
                'dragon_type' => $data->dragonType,
                'dragon_color' => $data->dragonCard->color(),
                'dragon_range' => $data->dragonCard->range(),
                'tiger_result' => $data->tigerResult,
                'tiger_type' => $data->tigerType,
                'tiger_color' => $data->tigerCard->color(),
                'tiger_range' => $data->tigerCard->range(),
                'winner' => $data->winner(),
                'result_submitted_at' => Date::now(),
            ]);
    }
}
