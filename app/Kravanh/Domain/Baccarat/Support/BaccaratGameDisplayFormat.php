<?php

namespace App\Kravanh\Domain\Baccarat\Support;

use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;

final class BaccaratGameDisplayFormat
{

    public function __construct(public readonly BaccaratGame $game)
    {
    }

    public static function format(BaccaratGame $game): BaccaratGameDisplayFormat
    {
        return new BaccaratGameDisplayFormat($game);
    }

    public function playerResultAsHtml(): string
    {
        return $this->resultAsHtml(
            type: 'player',
            number: $this->game->player_points,
            showRange: true);
    }

    public function bankerResultAsHtml(): string
    {
        return $this->resultAsHtml(
            type: 'banker',
            number: $this->game->banker_points,
            showRange: true);
    }

    public function resultAsHtml(
        ?string $type,
        ?int    $number,
        bool    $showRange = false
    ): string
    {
        if (is_null($type) && is_null($number)) {
            return '';
        }

        $card = BaccaratCard::make($type, $number);

        $icon = $card->icon();

        if ($showRange) {
            $icon .= ' (' . $card->range() . ')';
        }

        return <<<HTML
                <div class="flex items-center w-full px-2">
                  <span class="flex-none w-8 pl-2 font-medium">{$card->label()}</span> $icon
                </div>
                HTML;
    }

}
