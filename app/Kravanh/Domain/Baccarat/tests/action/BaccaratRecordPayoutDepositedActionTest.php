<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratRecordPayoutDepositedAction;
use App\Kravanh\Domain\Baccarat\Models\BaccaratPayoutDeposited;

use function Pest\Laravel\assertDatabaseCount;

test('it can create and validate payout deposit in baccarat game', function () {

    $deposit = (new BaccaratRecordPayoutDepositedAction())(
        baccaratGameId: 1,
        memberId: 2,
        transactionId: 3,
        amount: 4000,
        ticketIds: '1,2'
    );

    assertDatabaseCount(BaccaratPayoutDeposited::class, 1);

    $deposit->refresh();

    expect($deposit->baccarat_game_id)->toBe(1)
        ->and($deposit->member_id)->toBe(2)
        ->and($deposit->transaction_id)->toBe(3)
        ->and((int) $deposit->amount)->toBe(4000)
        ->and($deposit->ticket_ids)->toBe('1,2')
        ->and($deposit->rollback_transaction_id)->toBe(0);

});
