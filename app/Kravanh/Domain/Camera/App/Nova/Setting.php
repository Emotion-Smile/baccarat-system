<?php

namespace App\Kravanh\Domain\Camera\App\Nova;

use App\Kravanh\Domain\Game\Supports\GameName;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\Number;

class Setting
{
    public static function fields(): array
    {
        return collect([
            GameName::DragonTiger, 
            GameName::Baccarat
        ])
            ->flatMap(function ($game) {
                return [
                    $game->name => [
                        Heading::make('After Closed Bet'),

                        Number::make('Delay (Seconds)', "{$game->value}_camera_delay_after_closed_bet"),

                        Number::make('Preset Number', "{$game->value}_camera_preset_number_after_closed_bet"),

                        Heading::make('After Submitted Result'),

                        Number::make('Delay (Seconds)', "{$game->value}_camera_delay_after_submitted_result"),
        
                        Number::make('Preset Number', "{$game->value}_camera_preset_number_after_submitted_result"),
                    ]
                ];
            })
            ->toArray();
    }
}