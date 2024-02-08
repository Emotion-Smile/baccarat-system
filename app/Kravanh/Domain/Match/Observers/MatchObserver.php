<?php

namespace App\Kravanh\Domain\Match\Observers;

use App\Kravanh\Domain\Match\Models\Matches;

class MatchObserver
{
    public bool $afterCommit = true;

    public function updated(Matches $match)
    {
        $match->liveRefreshCache();
    }

}
