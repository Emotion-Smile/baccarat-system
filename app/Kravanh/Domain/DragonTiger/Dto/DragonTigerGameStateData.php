<?php

namespace App\Kravanh\Domain\DragonTiger\Dto;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;

final class DragonTigerGameStateData
{
    public function __construct(
        public readonly int $tableId,
        public readonly string $gameNumber,
        public readonly string $betStatus,
        public readonly int $bettingInterval,
        public readonly string $mainResult,
        public readonly string $subResult,
        public readonly int $dragonResult,
        public readonly string $dragonType,
        public readonly int $tigerResult,
        public readonly string $tigerType,
        public readonly string $event = 'submit'

    ) {

    }

    public static function from(DragonTigerGame $game, string $event = 'submit'): self
    {
        return new self(
            tableId: $game->game_table_id,
            gameNumber: $game->gameNumber(),
            betStatus: $game->betStatus(),
            bettingInterval: $game->bettingInterval(),
            mainResult: $game->mainResult(),
            subResult: $game->subResult(),
            dragonResult: $game->dragon_result ?? 0,
            dragonType: $game->dragon_type ?? '',
            tigerResult: $game->tiger_result ?? 0,
            tigerType: $game->tiger_type ?? '',
            event: $event
        );
    }

    public static function default(int $gameTableId): self
    {
        return new self(
            tableId: $gameTableId,
            gameNumber: '#',
            betStatus: 'close',
            bettingInterval: 0,
            mainResult: '',
            subResult: '',
            dragonResult: 0,
            dragonType: '',
            tigerResult: 0,
            tigerType: ''
        );
    }
}
