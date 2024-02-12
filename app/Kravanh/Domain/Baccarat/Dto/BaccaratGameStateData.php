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
        public readonly ?int $playerFirstCardValue,
        public readonly string $playerFirstCardType,
        public readonly string $playerFirstCardColor,
        public readonly ?int $playerFirstCardPoints,
        public readonly ?int $playerSecondCardValue,
        public readonly string $playerSecondCardType,
        public readonly string $playerSecondCardColor,
        public readonly ?int $playerSecondCardPoints,
        public readonly ?int $playerThirdCardValue,
        public readonly string $playerThirdCardType,
        public readonly string $playerThirdCardColor,
        public readonly ?int $playerThirdCardPoints,
        public readonly ?int $playerTotalPoints,
        public readonly ?int $playerPoints,
        public readonly ?int $bankerFirstCardValue,
        public readonly string $bankerFirstCardType,
        public readonly string $bankerFirstCardColor,
        public readonly ?int $bankerFirstCardPoints,
        public readonly ?int $bankerSecondCardValue,
        public readonly string $bankerSecondCardType,
        public readonly string $bankerSecondCardColor,
        public readonly ?int $bankerSecondCardPoints,
        public readonly ?int $bankerThirdCardValue,
        public readonly string $bankerThirdCardType,
        public readonly string $bankerThirdCardColor,
        public readonly ?int $bankerThirdCardPoints,
        public readonly ?int $bankerTotalPoints,
        public readonly ?int $bankerPoints,
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
            playerFirstCardValue: $game->player_first_card_value,
            playerFirstCardType: $game->player_first_card_type ?? '',
            playerFirstCardColor: $game->player_first_card_color ?? '',
            playerFirstCardPoints: $game->player_first_card_points,
            playerSecondCardValue: $game->player_second_card_value,
            playerSecondCardType: $game->player_second_card_type ?? '',
            playerSecondCardColor: $game->player_second_card_color ?? '',
            playerSecondCardPoints: $game->player_second_card_points,
            playerThirdCardValue: $game->player_third_card_value ?? null,
            playerThirdCardType: $game->player_third_card_type ?? '',
            playerThirdCardColor: $game->player_third_card_color ?? '',
            playerThirdCardPoints: $game->player_third_card_points ?? null,
            playerTotalPoints: $game->player_total_points,
            playerPoints: $game->player_points,
            bankerFirstCardValue: $game->banker_first_card_value,
            bankerFirstCardType: $game->banker_first_card_type ?? '',
            bankerFirstCardColor: $game->banker_first_card_color ?? '',
            bankerFirstCardPoints: $game->banker_first_card_points,
            bankerSecondCardValue: $game->banker_second_card_value,
            bankerSecondCardType: $game->banker_second_card_type ?? '',
            bankerSecondCardColor: $game->banker_second_card_color ?? '',
            bankerSecondCardPoints: $game->banker_second_card_points,
            bankerThirdCardValue: $game->banker_third_card_value ?? null,
            bankerThirdCardType: $game->banker_third_card_type ?? '',
            bankerThirdCardColor: $game->banker_third_card_color ?? '',
            bankerThirdCardPoints: $game->banker_third_card_points ?? null,
            bankerTotalPoints: $game->banker_total_points,
            bankerPoints: $game->banker_points,
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
            playerFirstCardValue: null,
            playerFirstCardType: '',
            playerFirstCardColor: '',
            playerFirstCardPoints: null,
            playerSecondCardValue: null,
            playerSecondCardType: '',
            playerSecondCardColor: '',
            playerSecondCardPoints: null,
            playerThirdCardValue: null,
            playerThirdCardType: '',
            playerThirdCardColor: '',
            playerThirdCardPoints: null,
            playerTotalPoints: null,
            playerPoints: null,
            bankerFirstCardValue: null,
            bankerFirstCardType: '',
            bankerFirstCardColor: '',
            bankerFirstCardPoints: null,
            bankerSecondCardValue: null,
            bankerSecondCardType: '',
            bankerSecondCardColor: '',
            bankerSecondCardPoints: null,
            bankerThirdCardValue: null,
            bankerThirdCardType: '',
            bankerThirdCardColor: '',
            bankerThirdCardPoints: null,
            bankerTotalPoints: null,
            bankerPoints: null
        );
    }
}
