<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Game\Models\GameTableCondition;
use App\Kravanh\Domain\User\Models\Member;
use Illuminate\Database\Eloquent\Collection;

final class BaccaratGameMemberGetUplineShareCommissionAction
{
    public function __invoke(Member $member): Collection
    {
        return GameTableCondition::query()
            ->whereIn('user_id', [
                $member->super_senior,
                $member->senior,
                $member->master_agent,
                $member->agent,
                $member->id
            ])
            ->select(['user_type', 'share_and_commission'])
            ->get();
    }
}
