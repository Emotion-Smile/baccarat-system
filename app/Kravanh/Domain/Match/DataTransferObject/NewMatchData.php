<?php

namespace App\Kravanh\Domain\Match\DataTransferObject;

use App\Kravanh\Domain\Match\Models\Matches;
use Illuminate\Http\Request;

class NewMatchData
{
    public function __construct(
        public int   $environmentId,
        public int   $groupId,
        public int   $userId,
        public float $totalPayout,
        public float $meronPayout,
        public ?int  $fightNumber = null,
        public ?int  $startedAt = null,
        public ?int  $betOpen = null,
        public ?int  $betDuration = 30,
    )
    {

    }

    public static function fromRequest(Request $request): NewMatchData
    {
        $user = $request->user();

        return new NewMatchData(
            $user->environment_id,
            $user->group_id,
            $user->id,
            $request->totalPayout,
            $request->meronPayout,
            fightNumber: $request->fightNumber ?? Matches::nextFightNumber($user->group_id),
        );
    }
}
