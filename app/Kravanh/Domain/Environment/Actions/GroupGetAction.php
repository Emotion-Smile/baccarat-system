<?php

namespace App\Kravanh\Domain\Environment\Actions;

use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\UserOption\Actions\UserOptionHasUseSecondTraderAction;
use App\Models\User;

class GroupGetAction
{
    /**
     * @param array $disableGroupId
     * @param User $user
     * @return Group[]
     */
    public function __invoke(array $disableGroupId, $user)
    {
        $useSecondTrader = app(UserOptionHasUseSecondTraderAction::class)($user->super_senior);

        return Group::select([
            'id',
            'name',
            'streaming_name',
            'streaming_logo',
            'css_style',
            'meron',
            'wala'
        ])
            ->where('active', true)
            ->where('use_second_trader', $useSecondTrader)
            ->whereNotIn('id', $disableGroupId)
            ->orderBy('order')
            ->get();
    }
}
