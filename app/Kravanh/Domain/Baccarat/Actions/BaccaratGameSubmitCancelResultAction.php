<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Support\BaccaratGameWinner;
use Illuminate\Support\Facades\Date;

final class BaccaratGameSubmitCancelResultAction
{
    public function __invoke(
        int $dragonTigerGameId,
        int $userId
    ): bool {
        return (bool) BaccaratGame::query()
            ->where('id', $dragonTigerGameId)
            ->update([
                'result_submitted_user_id' => $userId,
                'winner' => BaccaratGameWinner::Cancel,
                'result_submitted_at' => Date::now(),
            ]);
    }
}
