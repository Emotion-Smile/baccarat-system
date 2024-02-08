<?php

namespace App\Kravanh\Domain\Integration\Actions\Api;

use App\Kravanh\Domain\User\Models\Member;

class GenerateRollbackPayoutMetaAction
{
    public function __invoke(
        Member $member,
        string $game,
        int $amount,
        array $meta
    ): array
    {
        return match ($game) {
            'yuki' => (new GenerateYukiRollbackPayoutMetaAction)(
                member: $member, 
                amount: $amount,
                meta: $meta
            ),

            'sport' => (new GenerateSportRollbackPayoutMetaAction)(
                member: $member, 
                amount: $amount,
                meta: $meta
            ), 

            default => [] 
        };
    }
}