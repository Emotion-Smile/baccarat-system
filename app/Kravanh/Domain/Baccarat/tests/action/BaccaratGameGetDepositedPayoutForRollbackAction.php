<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetDepositedPayoutForRollbackAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratPayoutProcessingManagerAction;
use App\Kravanh\Domain\Baccarat\Models\BaccaratPayoutDeposited;
use App\Kravanh\Domain\Baccarat\tests\BaccaratTestHelper;
use App\Kravanh\Domain\User\Models\Member;

test('verifies retrieval of correct transactions for payout rollback in Dragon Tiger game', function () {

    $game = BaccaratTestHelper::createGameIncludeWinAndLoseTickets();

    (new BaccaratPayoutProcessingManagerAction())(baccaratGameId: $game->id);

    $payouts = (new BaccaratGameGetDepositedPayoutForRollbackAction())($game->id);

    expect($payouts->count())->toBe(4)
        ->and($payouts->first()->toArray())->toHaveKeys(['id', 'member_id', 'amount', 'member', 'dragon_tiger_game_id'])
        ->and($payouts->first()->member)->toBeInstanceOf(Member::class);

    BaccaratPayoutDeposited::query()
        ->where('dragon_tiger_game_id', $game->id)
        ->update(['rollback_transaction_id' => 1]);

    $payouts = (new BaccaratGameGetDepositedPayoutForRollbackAction())($game->id);
    expect($payouts->count())->toBe(0);

});
