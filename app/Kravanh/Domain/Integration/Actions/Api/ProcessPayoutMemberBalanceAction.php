<?php

namespace App\Kravanh\Domain\Integration\Actions\Api;

use App\Kravanh\Domain\Integration\Actions\T88\ProcessPayoutMemberBalanceAction as T88ProcessPayoutMemberBalanceAction;
use App\Kravanh\Domain\Integration\Supports\Enums\Game;
use App\Kravanh\Domain\User\Models\Member;
use Illuminate\Http\JsonResponse;

class ProcessPayoutMemberBalanceAction
{
    public function __invoke(
        string $game,
        array $meta,
        int $amount,
        Member $member
    ): JsonResponse
    {
        return match($game) {
            Game::YUKI => T88ProcessPayoutMemberBalanceAction::make(
                game: $game,
                meta: $meta,
                amount: $amount,
                member: $member,
            ) 
        };
    }

    public static function make(
        string $game,
        array $meta,
        int $amount,
        Member $member
    )
    {
        return (new static)(
            game: $game,
            meta: $meta,
            amount: $amount,
            member: $member,
        );
    }
} 