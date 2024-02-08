<?php

namespace App\Kravanh\Domain\DragonTiger\Dto;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;

final class DragonTigerGameLiveData
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

    public static function from(int $gameTableId, DragonTigerGame $game): DragonTigerGameLiveData
    {
        return new DragonTigerGameLiveData(
            tableId: $gameTableId,
            gameNumber: $game->gameNumber(),
            startAt: $game->started_at,
            closeBetAt: $game->closed_bet_at,
            betStatus: $game->canBet() ? 'open' : 'close',
            interval: $game->bettingInterval(),
        );
    }

    public static function default(int $gameTableId): DragonTigerGameLiveData
    {
        return new DragonTigerGameLiveData(
            tableId: $gameTableId,
            gameNumber: '#',
            startAt: '',
            closeBetAt: '',
            betStatus: 'close',
            interval: 0
        );
    }
}
