<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;

final class BaccaratGameGetLiveIdsAction
{
    public function __invoke(): array
    {
        return BaccaratGame::query()
            ->select(['id'])
            ->whereLiveGame()
            ->get()
            ->toArray();
    }
}
