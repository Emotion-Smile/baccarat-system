<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerRecordPayoutDepositedAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerPayoutDeposited;

use function Pest\Laravel\assertDatabaseCount;

test('it can create and validate payout deposit in Dragon Tiger game', function () {

    $deposit = (new DragonTigerRecordPayoutDepositedAction())(
        dragonTigerGameId: 1,
        memberId: 2,
        transactionId: 3,
        amount: 4000,
        ticketIds: '1,2'
    );

    assertDatabaseCount(DragonTigerPayoutDeposited::class, 1);

    $deposit->refresh();

    expect($deposit->dragon_tiger_game_id)->toBe(1)
        ->and($deposit->member_id)->toBe(2)
        ->and($deposit->transaction_id)->toBe(3)
        ->and((int) $deposit->amount)->toBe(4000)
        ->and($deposit->ticket_ids)->toBe('1,2')
        ->and($deposit->rollback_transaction_id)->toBe(0);

});
