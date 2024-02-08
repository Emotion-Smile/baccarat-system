<?php

namespace App\Kravanh\Domain\User\Actions;

use App\Models\User;

class ForceUpdateMatchConditionFromUpLineAction 
{
    public function __invoke($user)
    {
        return User::where($user->type, $user->id)
            ->update([
                'condition->minimum_bet_per_ticket' => $user->condition['minimum_bet_per_ticket'],
                'condition->maximum_bet_per_ticket' => $user->condition['maximum_bet_per_ticket'],
                'condition->match_limit' => $user->condition['match_limit'],
            ]);
    }
}