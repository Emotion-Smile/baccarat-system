<?php

namespace Kravanh\BetCondition\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class GetGroupsController
{
    public function __invoke(): Collection
    {
        return DB::table('groups')
            ->select(['id', 'name'])
            ->where('active', 1)
            ->where('use_second_trader', 0)
            ->orderBy('order')
            ->get();
    }
}
