<?php

namespace App\Kravanh\Domain\Integration\Actions\Api;

use App\Kravanh\Domain\Integration\Supports\Enums\Game;
use App\Kravanh\Domain\User\Models\Member;

class GeneratePayoutMetaAction
{
    public function __invoke(
        Member $member,
        string $game,
        int $amount,
        array $meta
    ): array
    {
        return match ($game) {
            Game::YUKI => (new GenerateYukiPayoutMetaAction)(
                member: $member, 
                amount: $amount,
                meta: $meta
            ),

            Game::SPORT => (new GenerateSportPayoutMetaAction)(
                member: $member, 
                amount: $amount,
                meta: $meta
            ), 

            default => [] 
        };
    }

    public static function make(
        Member $member,
        string $game,
        int $amount,
        array $meta
    ): array 
    {
        return (new static)(
            member: $member,
            game: $game,
            amount: $amount,
            meta: $meta
        );
    }
}