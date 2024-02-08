<?php

namespace App\Kravanh\Domain\DragonTiger\Support\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class DragonTigerGameQueryBuilder extends Builder
{
    //https://martinjoo.dev/build-your-own-laravel-query-builders
    public function whereLiveGame(): static
    {
        $this->query->whereNull([
            'result_submitted_user_id',
            'dragon_result',
            'dragon_type',
            'tiger_result',
            'tiger_type',
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
