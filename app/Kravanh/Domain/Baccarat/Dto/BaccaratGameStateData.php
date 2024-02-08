<?php

namespace App\Kravanh\Domain\Baccarat\Dto;

use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;

final class BaccaratGameStateData
{
    public function __construct(
        public readonly int $tableId,
        public readonly string $gameNumber,
        public readonly string $betStatus,
        public readonly int $bettingInterval,
        public readonly string $mainResult,
        public readonly string $subResult,
        public readonly int $playerFirstCardValue,
        public readonly string $playerFirstCardType,
        public readonly string $playerFirstCardColor,
        public readonly int $playerSecondCardValue,
        public readonly string $playerSecondCardType,
        public readonly string $playerSecondCardColor,
        public readonly int $playerThirdCardValue,
        public readonly string $playerThirdCardType,
        public readonly string $playerThirdCardColor,
        public readonly string $event = 'submit'

    ) {

    }

    public static function from(BaccaratGame $game, string $event = 'submit'): self
    {
        return new self(
            tableId: $game->game_table_id,
            gameNumber: $game->gameNumber(),
            betStatus: $game->betStatus(),
            bettingInterval: $game->bettingInterval(),
            mainResult: $game->mainResult(),
            subResult: $game->subResult(),
            playerFirstCardValue: $game->player_first_card_value ?? 0,
            playerFirstCardType: $game->player_first_card_type ?? '',
            playerFirstCardColor: $game->player_first_card_color ?? '',
            playerSecondCardValue: $game->player_second_card_value ?? 0,
            playerSecondCardType: $game->player_second_card_type ?? '',
            playerSecondCardColor: $game->player_second_card_color ?? '',
            playerThirdCardValue: $game->player_third_card_value ?? 0,
            playerThirdCardType: $game->player_third_card_type ?? '',
            playerThirdCardColor: $game->player_third_card_color ?? '',
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
            playerFirstCardValue: 0,
            playerFirstCardType: '',
            playerFirstCardColor: '',
            playerSecondCardValue: 0,
            playerSecondCardType: '',
            playerSecondCardColor: '',
            playerThirdCardValue: 0,
            playerThirdCardType: '',
            playerThirdCardColor: ''
        );
    }
}
