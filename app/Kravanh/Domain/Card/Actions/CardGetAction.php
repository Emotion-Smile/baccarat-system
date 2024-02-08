<?php

namespace App\Kravanh\Domain\Card\Actions;

use Illuminate\Support\Collection;

final class CardGetAction
{
    public function __invoke(int $code)
    {
        return $this->cards()->where('code', $code)->firstOrFail();
    }

    private function cards(): Collection
    {
        return Collection::make([
            ['code' => 1010, 'name' => 'ace_of_spades', 'value' => 1, 'type' => 'spade'],
            ['code' => 1020, 'name' => '_2_of_spades', 'value' => 2, 'type' => 'spade'],
            ['code' => 1030, 'name' => '_3_of_spades', 'value' => 3, 'type' => 'spade'],
            ['code' => 1040, 'name' => '_4_of_spades', 'value' => 4, 'type' => 'spade'],
            ['code' => 1050, 'name' => '_5_of_spades', 'value' => 5, 'type' => 'spade'],
            ['code' => 1060, 'name' => '_6_of_spades', 'value' => 6, 'type' => 'spade'],
            ['code' => 1070, 'name' => '_7_of_spades', 'value' => 7, 'type' => 'spade'],
            ['code' => 1080, 'name' => '_8_of_spades', 'value' => 8, 'type' => 'spade'],
            ['code' => 1090, 'name' => '_9_of_spades', 'value' => 9, 'type' => 'spade'],
            ['code' => 1100, 'name' => '_10_of_spades', 'value' => 10, 'type' => 'spade'],
            ['code' => 1110, 'name' => 'jack_of_spades', 'value' => 11, 'type' => 'spade'],
            ['code' => 1120, 'name' => 'queen_of_spades', 'value' => 12, 'type' => 'spade'],
            ['code' => 1130, 'name' => 'king_of_spades', 'value' => 13, 'type' => 'spade'],
            ['code' => 2010, 'name' => 'ace_of_hearts', 'value' => 1, 'type' => 'heart'],
            ['code' => 2020, 'name' => '_2_of_hearts', 'value' => 2, 'type' => 'heart'],
            ['code' => 2030, 'name' => '_3_of_hearts', 'value' => 3, 'type' => 'heart'],
            ['code' => 2040, 'name' => '_4_of_hearts', 'value' => 4, 'type' => 'heart'],
            ['code' => 2050, 'name' => '_5_of_hearts', 'value' => 5, 'type' => 'heart'],
            ['code' => 2060, 'name' => '_6_of_hearts', 'value' => 6, 'type' => 'heart'],
            ['code' => 2070, 'name' => '_7_of_hearts', 'value' => 7, 'type' => 'heart'],
            ['code' => 2080, 'name' => '_8_of_hearts', 'value' => 8, 'type' => 'heart'],
            ['code' => 2090, 'name' => '_9_of_hearts', 'value' => 9, 'type' => 'heart'],
            ['code' => 2100, 'name' => '_10_of_hearts', 'value' => 10, 'type' => 'heart'],
            ['code' => 2110, 'name' => 'jack_of_hearts', 'value' => 11, 'type' => 'heart'],
            ['code' => 2120, 'name' => 'queen_of_hearts', 'value' => 12, 'type' => 'heart'],
            ['code' => 2130, 'name' => 'king_of_hearts', 'value' => 13, 'type' => 'heart'],
            ['code' => 3010, 'name' => 'ace_of_clubs', 'value' => 1, 'type' => 'club'],
            ['code' => 3020, 'name' => '_2_of_clubs', 'value' => 2, 'type' => 'club'],
            ['code' => 3030, 'name' => '_3_of_clubs', 'value' => 3, 'type' => 'club'],
            ['code' => 3040, 'name' => '_4_of_clubs', 'value' => 4, 'type' => 'club'],
            ['code' => 3050, 'name' => '_5_of_clubs', 'value' => 5, 'type' => 'club'],
            ['code' => 3060, 'name' => '_6_of_clubs', 'value' => 6, 'type' => 'club'],
            ['code' => 3070, 'name' => '_7_of_clubs', 'value' => 7, 'type' => 'club'],
            ['code' => 3080, 'name' => '_8_of_clubs', 'value' => 8, 'type' => 'club'],
            ['code' => 3090, 'name' => '_9_of_clubs', 'value' => 9, 'type' => 'club'],
            ['code' => 3100, 'name' => '_10_of_clubs', 'value' => 10, 'type' => 'club'],
            ['code' => 3110, 'name' => 'jack_of_clubs', 'value' => 11, 'type' => 'club'],
            ['code' => 3120, 'name' => 'queen_of_clubs', 'value' => 12, 'type' => 'club'],
            ['code' => 3130, 'name' => 'king_of_clubs', 'value' => 13, 'type' => 'club'],
            ['code' => 4010, 'name' => 'ace_of_diamonds', 'value' => 1, 'type' => 'diamond'],
            ['code' => 4020, 'name' => '_2_of_diamonds', 'value' => 2, 'type' => 'diamond'],
            ['code' => 4030, 'name' => '_3_of_diamonds', 'value' => 3, 'type' => 'diamond'],
            ['code' => 4040, 'name' => '_4_of_diamonds', 'value' => 4, 'type' => 'diamond'],
            ['code' => 4050, 'name' => '_5_of_diamonds', 'value' => 5, 'type' => 'diamond'],
            ['code' => 4060, 'name' => '_6_of_diamonds', 'value' => 6, 'type' => 'diamond'],
            ['code' => 4070, 'name' => '_7_of_diamonds', 'value' => 7, 'type' => 'diamond'],
            ['code' => 4080, 'name' => '_8_of_diamonds', 'value' => 8, 'type' => 'diamond'],
            ['code' => 4090, 'name' => '_9_of_diamonds', 'value' => 9, 'type' => 'diamond'],
            ['code' => 4100, 'name' => '_10_of_diamonds', 'value' => 10, 'type' => 'diamond'],
            ['code' => 4110, 'name' => 'jack_of_diamonds', 'value' => 11, 'type' => 'diamond'],
            ['code' => 4120, 'name' => 'queen_of_diamonds', 'value' => 12, 'type' => 'diamond'],
            ['code' => 4130, 'name' => 'king_of_diamonds', 'value' => 13, 'type' => 'diamond'],
        ]);
    }
}
