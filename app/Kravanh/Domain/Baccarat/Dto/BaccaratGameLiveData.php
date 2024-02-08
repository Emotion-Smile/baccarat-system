<?php

namespace App\Kravanh\Domain\Baccarat\Dto;

use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;

final class BaccaratGameLiveData
{
    public function __construct(
        public readonly int    $tableId,
        public readonly string $gameNumber,
        public readonly string $startAt,
        public readonly string $closeBetAt,
        public readonly string $betStatus,
        public readonly int    $interval,
    )
    {

    }

    public static function from(int $gameTableId, BaccaratGame $game): BaccaratGameLiveData
    {
        return new BaccaratGameLiveData(
            tableId: $gameTableId,
            gameNumber: $game->gameNumber(),
            startAt: $game->started_at,
            closeBetAt: $game->closed_bet_at,
            betStatus: $game->canBet() ? 'open' : 'close',
            interval: $game->bettingInterval(),
        );
    }

    public static function default(int $gameTableId): BaccaratGameLiveData
    {
        return new BaccaratGameLiveData(
            tableId: $gameTableId,
            gameNumber: '#',
            startAt: '',
            closeBetAt: '',
            betStatus: 'close',
            interval: 0
        );
    }
}
