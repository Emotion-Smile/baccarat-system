<?php

namespace App\Kravanh\Domain\GroupUser\Actions;

use Illuminate\Support\Facades\DB;

class GroupGetIdsBelongToUser
{
    public function __invoke($user): array
    {
        return DB::table('group_user')
            ->select('group_id')
            ->whereIn('user_id', [$user->id, $user->agent])
            ->pluck('group_id')
            ->unique()
            ->toArray();
    }
}
