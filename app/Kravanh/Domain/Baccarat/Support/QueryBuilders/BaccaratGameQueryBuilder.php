<?php

namespace App\Kravanh\Domain\Baccarat\Support\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class BaccaratGameQueryBuilder extends Builder
{
    //https://martinjoo.dev/build-your-own-laravel-query-builders
    public function whereLiveGame(): static
    {
        $this->query->whereNull([
            'result_submitted_user_id',
            'player_first_card_value',
            'player_first_card_type',
            'player_first_card_color',
            'player_first_card_points',
            'player_second_card_value',
            'player_second_card_type',
            'player_second_card_color',
            'player_second_card_points',
            'player_third_card_value',
            'player_third_card_type',
            'player_third_card_color',
            'player_third_card_points',
            'player_total_points',
            'player_points',
            'banker_first_card_value',
            'banker_first_card_type',
            'banker_first_card_color',
            'banker_first_card_points',
            'banker_second_card_value',
            'banker_second_card_type',
            'banker_second_card_color',
            'banker_second_card_points',
            'banker_third_card_value',
            'banker_third_card_type',
            'banker_third_card_color',
            'banker_third_card_points',
            'banker_total_points',
            'banker_points',
            'result_submitted_at',
            'winner',
        ]);

        return $this;
    }

    public function excludeLiveGame(): static
    {
        $this->query->whereNotNull([
            'winner',
        ]);

        return $this;
    }
}
