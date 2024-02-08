<?php

namespace App\Kravanh\Domain\Baccarat\Dto;

use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\Game\Supports\GameName;
use App\Kravanh\Domain\User\Models\Member;

final class TransactionTicketBettingMetaData
{
    public string $game;
    public string $type = 'bet';

    public function __construct(
        public readonly int    $ticketId,
        public readonly int    $gameId,
        public readonly int    $beforeBalance,
        public readonly int    $currentBalance,
        public readonly string $gameNumber,
        public int             $amount,
        public string          $betOn,
        public int|float       $payoutRate,
        public int             $payout,
        public string          $currency,
    )
    {
        $this->game = GameName::DragonTiger->value;
    }

    public static function fromMeta(array $meta): TransactionTicketBettingMetaData
    {
        return (new  TransactionTicketBettingMetaData(
            ticketId: $meta['bet_id'],
            gameId: $meta['match_id'],
            beforeBalance: $meta['before_balance'],
            currentBalance: $meta['current_balance'],
            gameNumber: $meta['fight_number'],
            amount: $meta['amount'],
            betOn: $meta['bet_on'],
            payoutRate: $meta['payout_rate'],
            payout: $meta['payout'],
            currency: $meta['currency']
        ));
    }

    public function toArray(): array
    {
        return [
            'game' => GameName::DragonTiger,
            'bet_id' => $this->ticketId,
            'match_id' => $this->gameId,
            'type' => $this->type,
            'before_balance' => $this->beforeBalance,
            'current_balance' => $this->currentBalance,
            'fight_number' => $this->gameNumber,
            'amount' => $this->amount,
            'bet_on' => $this->betOn,
            'payout_rate' => $this->payoutRate,
            'payout' => $this->payout,
            'currency' => $this->currency
        ];
    }

    public static function from(
        BaccaratGame   $game,
        BaccaratTicket $ticket,
        Member            $member,
        int               $beforeBalance,
        int               $currentBalance,
    ): TransactionTicketBettingMetaData
    {
        return (new TransactionTicketBettingMetaData(
            ticketId: $ticket->id,
            gameId: $game->id,
            beforeBalance: $beforeBalance,
            currentBalance: $currentBalance,
            gameNumber: $game->gameNumber(),
            amount: $ticket->amount,
            betOn: $ticket->betOn(),
            payoutRate: $ticket->payout_rate,
            payout: $ticket->payout,
            currency: $member->currency
        ));
    }
}
