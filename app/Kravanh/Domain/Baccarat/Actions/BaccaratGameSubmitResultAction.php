<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameSubmitResultData;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameSubmitResultBetOpenException;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use Illuminate\Support\Facades\Date;


final class BaccaratGameSubmitResultAction
{
    /**
     * @throws BaccaratGameSubmitResultBetOpenException
     */
    public static function from(BaccaratGameSubmitResultData $data): bool
    {
        return (new BaccaratGameSubmitResultAction())($data);
    }

    /**
     * @throws BaccaratGameSubmitResultBetOpenException
     */
    public function __invoke(BaccaratGameSubmitResultData $data): bool
    {

        $game = BaccaratGame::find($data->baccaratGameId);

        if (! $game->isBetClosed()) {
            throw new BaccaratGameSubmitResultBetOpenException();
        }

        return (bool) BaccaratGame::query()
            ->where('id', $data->baccaratGameId)
            ->update([
                'result_submitted_user_id' => $data->user->id,
                'dragon_result' => $data->playerResult,
                'dragon_type' => $data->playerType,
                'dragon_color' => $data->playerCard->color(),
                'dragon_range' => $data->playerCard->range(),
                'tiger_result' => $data->bankerResult,
                'tiger_type' => $data->bankerType,
                'tiger_color' => $data->bankerCard->color(),
                'tiger_range' => $data->bankerCard->range(),
                'winner' => $data->winner(),
                'result_submitted_at' => Date::now(),
            ]);
    }
}
