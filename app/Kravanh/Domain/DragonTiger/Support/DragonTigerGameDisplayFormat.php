<?php

namespace App\Kravanh\Domain\DragonTiger\Support;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;

final class DragonTigerGameDisplayFormat
{

    public function __construct(public readonly DragonTigerGame $game)
    {
    }

    public static function format(DragonTigerGame $game): DragonTigerGameDisplayFormat
    {
        return new DragonTigerGameDisplayFormat($game);
    }

    public function dragonResultAsHtml(): string
    {
        return $this->resultAsHtml(
            type: $this->game->dragon_type,
            number: $this->game->dragon_result,
            showRange: true);
    }

    public function tigerResultAsHtml(): string
    {
        return $this->resultAsHtml(
            type: $this->game->tiger_type,
            number: $this->game->tiger_result,
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

        $card = DragonTigerCard::make($type, $number);

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
