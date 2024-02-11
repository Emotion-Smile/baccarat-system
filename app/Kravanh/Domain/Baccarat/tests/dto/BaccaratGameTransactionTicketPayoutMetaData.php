<?php

use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameTransactionTicketPayoutMetaData;
use App\Kravanh\Domain\Game\Supports\GameName;

test('it can create payout transaction meta correctly', function () {

    $meta = BaccaratGameTransactionTicketPayoutMetaData::from(
        ticketIds: '1,2,3',
        gameId: 1,
        amount: 1000,
        currency: 'KHR',
        beforeBalance: 0,
        currentBalance: 1000,
        gameNumber: '1/1',
    );

    expect($meta)->toBeInstanceOf(BaccaratGameTransactionTicketPayoutMetaData::class)
        ->and($meta->game)->toBe('dragon_tiger')
        ->and($meta->type)->toBe('payout')
        ->and($meta->gameId)->toBe(1)
        ->and($meta->amount)->toBe(1000)
        ->and($meta->currency)->toBe('KHR')
        ->and($meta->beforeBalance)->toBe(0)
        ->and($meta->currentBalance)->toBe(1000)
        ->and($meta->gameNumber)->toBe('1/1')
        ->and($meta->toMeta())->toMatchArray([
            'game' => GameName::Baccarat,
            'type' => 'payout',
            'bet_id' => $meta->ticketIds,
            'match_id' => $meta->gameId,
            'before_balance' => $meta->beforeBalance,
            'current_balance' => $meta->currentBalance,
            'match_status' => '',
            'amount' => $meta->amount,
            'fight_number' => $meta->gameNumber,
            'currency' => $meta->currency,
        ]);

    $fromMeta = BaccaratGameTransactionTicketPayoutMetaData::fromMeta($meta->toMeta());

    expect($fromMeta->toMeta())->toBe($meta->toMeta());

});
