<?php

namespace App\Kravanh\Domain\Integration\Actions\Api;

use App\Kravanh\Domain\User\Models\Member;

class GenerateSportPayoutMetaAction
{
    public function __invoke(
        Member $member,
        int $amount,
        array $meta
    ): array
    {
        return [
            'game' => 'sport',
            'type' => $meta['type'],
            'before_balance' => $member->balanceInt - $amount,
            'current_balance' => $member->balanceInt,
            'bet_id' => $meta['ticket_id'] ?? '',
            'currency' => $member->currency,
            'remark' => $meta['remark'] ?? '',
            'fight_number' => $meta['ticket_sNo'] ?? '',
            'match_id' => $meta['match_id'] ?? '',
            'amount' => $amount,
            'action' => 'PayoutDepositorAction',
            'match_status' => $meta['result'] ?? ''
        ];
    }
}