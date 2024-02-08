<?php

namespace App\Kravanh\Domain\Match\Actions;

use App\Kravanh\Domain\Match\DataTransferObject\NewMatchData;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use Illuminate\Support\Facades\Date;

class CreateNewMatchAction
{
    public function __invoke(NewMatchData $match): Matches
    {
        return Matches::create([
            'user_id' => $match->userId,
            'environment_id' => $match->environmentId,
            'group_id' => $match->groupId,
            'fight_number' => $match->fightNumber,
            'match_date' => Date::today(),
            'payout_total' => $match->totalPayout,
            'payout_meron' => $match->meronPayout,
            'match_started_at' => $match->startedAt ?? Date::now(),
            'bet_duration' => $match->betDuration,
            'result' => MatchResult::NONE
        ]);
    }
}
