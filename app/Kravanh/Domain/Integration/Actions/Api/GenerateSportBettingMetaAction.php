<?php

namespace App\Kravanh\Domain\Integration\Actions\Api;

use App\Kravanh\Domain\User\Models\Member;

class GenerateSportBettingMetaAction
{
    public function __invoke(
        Member $member,
        int $amount,
        array $meta
    ): array
    {
        return [
            'game' => 'sport',
            'type' => 'bet',
            'before_balance' => $member->balanceInt + $amount,
            'current_balance' => $member->balanceInt,
            'bet_id' => $meta['ticket_id'],
            'currency' => $member->currency,
            'remark' => $meta['remark'],
            'fight_number' => $meta['ticket_sNo'],
            'match_id' => $meta['match_id'],
            'bet_on' => $meta['bet_on'] ?? '',
            'amount' => $amount,
            'payout_rate' => 0,
            'payout' => 0
        ];
    }
}