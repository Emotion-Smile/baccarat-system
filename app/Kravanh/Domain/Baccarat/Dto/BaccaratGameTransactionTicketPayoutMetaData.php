<?php

namespace App\Kravanh\Domain\Baccarat\Dto;

use App\Kravanh\Domain\Game\Supports\GameName;

final class BaccaratGameTransactionTicketPayoutMetaData
{
    public string $game;
    public string $type = 'payout';

    public function __construct(
        public readonly string $ticketIds,
        public readonly int    $gameId,
        public readonly int    $beforeBalance,
        public readonly int    $currentBalance,
        public readonly string $gameNumber,
        public readonly int    $amount,
        public readonly string $currency,
    )
    {
        $this->game = GameName::DragonTiger->value;
    }

    public static function fromMeta(array $meta): BaccaratGameTransactionTicketPayoutMetaData
    {
        return (new  BaccaratGameTransactionTicketPayoutMetaData(
            ticketIds: $meta['bet_id'],
            gameId: $meta['match_id'],
            beforeBalance: $meta['before_balance'],
            currentBalance: $meta['current_balance'],
            gameNumber: $meta['fight_number'],
            amount: $meta['amount'],
            currency: $meta['currency']
        ));
    }

    public function toMeta(): array
    {
        return [
            'game' => GameName::DragonTiger,
            'type' => $this->type,
            'bet_id' => $this->ticketIds,
            'match_id' => $this->gameId,
            'before_balance' => $this->beforeBalance,
            'current_balance' => $this->currentBalance,
            'match_status' => '',
            'amount' => $this->amount,
            'fight_number' => $this->gameNumber,
            'currency' => $this->currency,
        ];
    }

    public static function from(
        string $ticketIds,
        int    $gameId,
        int    $amount,
        string $currency,
        int    $beforeBalance,
        int    $currentBalance,
        string $gameNumber,
    ): BaccaratGameTransactionTicketPayoutMetaData
    {
        return (new BaccaratGameTransactionTicketPayoutMetaData(
            ticketIds: $ticketIds,
            gameId: $gameId,
            beforeBalance: $beforeBalance,
            currentBalance: $currentBalance,
            gameNumber: $gameNumber,
            amount: $amount,
            currency: $currency
        ));
    }
}
