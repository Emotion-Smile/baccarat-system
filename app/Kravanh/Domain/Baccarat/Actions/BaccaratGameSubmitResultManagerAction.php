<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameStateData;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameSubmitResultData;
use App\Kravanh\Domain\Baccarat\Events\BaccaratGameResultSubmitted;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameSubmitResultBetOpenException;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;

final class BaccaratGameSubmitResultManagerAction
{
    /**
     * @throws BaccaratGameSubmitResultBetOpenException
     */
    public function __invoke(BaccaratGameSubmitResultData $data): bool
    {

        $resultSubmitted = app(BaccaratGameSubmitResultAction::class)(data: $data);

        if (! $resultSubmitted) {
            return false;
        }

        BaccaratGameResultSubmitted::dispatchWithPayload(
            payload: BaccaratGameStateData::from(
                game: BaccaratGame::find($data->baccaratGameId)
            )
        );

        return true;
    }
}
