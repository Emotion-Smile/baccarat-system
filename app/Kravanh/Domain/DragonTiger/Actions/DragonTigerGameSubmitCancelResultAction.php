<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerGameWinner;
use Illuminate\Support\Facades\Date;

final class DragonTigerGameSubmitCancelResultAction
{
    public function __invoke(
        int $dragonTigerGameId,
        int $userId
    ): bool {
        return (bool) DragonTigerGame::query()
            ->where('id', $dragonTigerGameId)
            ->update([
                'result_submitted_user_id' => $userId,
                'winner' => DragonTigerGameWinner::Cancel,
                'result_submitted_at' => Date::now(),
            ]);
    }
}
