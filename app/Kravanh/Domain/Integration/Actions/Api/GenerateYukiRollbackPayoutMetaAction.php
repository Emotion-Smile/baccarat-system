<?php

namespace App\Kravanh\Domain\Integration\Actions\Api;

use App\Kravanh\Domain\User\Models\Member;

class GenerateYukiRollbackPayoutMetaAction
{
    public function __invoke(
        Member $member,
        int $amount,
        array $meta
    ): array
    {
        return [
            'game' => 'yuki',
            'type' => 'withdraw',
            'before_balance' => $member->balanceInt + $amount,
            'current_balance' => $member->balanceInt,
            'currency' => $member->currency,
            'note' => $meta['remark'],
            'match_id' => $meta['game_id'],
            'action' => 'modify_match',
            'mode' => 'company'
        ];
    }
}