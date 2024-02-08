<?php

namespace App\Kravanh\Domain\Match\Actions;

use App\Kravanh\Domain\Match\Models\Matches;
use Illuminate\Support\Facades\Cache;

class BlockMemberWithdrawBalanceAction
{

    public function __invoke(Matches $match)
    {

        $memberIds = $match->betRecords()
            ->pluck('user_id')
            ->unique()
            ->toArray();

        Cache::put('balance:withdraw:block', $memberIds, now()->addMinutes(10));

    }
}
