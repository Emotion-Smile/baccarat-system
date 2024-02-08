<?php

namespace App\Kravanh\Domain\Integration\Actions\Api;

use App\Kravanh\Domain\User\Models\Member;

class GenerateSportRollbackPayoutMetaAction
{
    public function __invoke(
        Member $member,
        int $amount,
        array $meta
    ): array
    {
        return [
            'game' => 'sport',
            'type' => 'withdraw',
            'before_balance' => $member->balanceInt + $amount,
            'current_balance' => $member->balanceInt,
            'currency' => $member->currency,
            'note' => $meta['remark'] ?? '',
            'match_id' => $meta['sport_match_option_id'],
            'action' => 'modify_match',
            'mode' => 'company'
        ];
    }
}