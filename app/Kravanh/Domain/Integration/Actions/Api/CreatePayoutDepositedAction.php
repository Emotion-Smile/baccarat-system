<?php

namespace App\Kravanh\Domain\Integration\Actions\Api;

use App\Kravanh\Domain\Integration\Actions\T88\CreatePayoutDepositedAction as T88CreatePayoutDepositedAction;
use App\Kravanh\Domain\Integration\Supports\Enums\Game;
use App\Kravanh\Domain\User\Models\Member;
use Bavix\Wallet\Models\Transaction;

class CreatePayoutDepositedAction
{
    public function __invoke(
        string $game,
        array $meta,
        Member $member,
        Transaction $transaction,
    )
    {
        return match ($game) {
            Game::YUKI => T88CreatePayoutDepositedAction::make(
                meta: $meta,
                member: $member,
                transaction: $transaction,
                depositor: 'deposit_missing'
            ),

            default => null
        };
    }

    public static function make(
        string $game,
        array $meta,
        Member $member,
        Transaction $transaction,
    )
    {
        return (new static)(
            game: $game,
            meta: $meta,
            member: $member,
            transaction: $transaction,
        );
    }
} 