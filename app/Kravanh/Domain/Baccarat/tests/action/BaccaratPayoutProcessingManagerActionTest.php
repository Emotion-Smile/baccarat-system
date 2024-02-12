<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratPayoutProcessingManagerAction;
use App\Kravanh\Domain\Baccarat\Models\BaccaratPayoutDeposited;
use App\Kravanh\Domain\Baccarat\tests\BaccaratTestHelper;
use App\Kravanh\Domain\Match\Events\AllPayoutDeposited;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Support\Facades\Event;

test('verifies regular payout processing in Dragon Tiger game', function () {

    Event::fake();

    $game = BaccaratTestHelper::createGameIncludeWinAndLoseTickets();

    (new BaccaratPayoutProcessingManagerAction())(baccaratGameId: $game->id);

    Event::assertDispatched(AllPayoutDeposited::class);

    expect($game->isTie())->toBeFalse()
        ->and(Transaction::count())->toBe(4)
        ->and(BaccaratPayoutDeposited::count())->toBe(4);


});

test('verifies payout processing for tie result in Dragon Tiger game', function () {

    $game = BaccaratTestHelper::createGameIncludeWinAndLoseTickets();
    $game->winner = 'tie';
    $game->saveQuietly();
    $game->refresh();

    (new BaccaratPayoutProcessingManagerAction())(baccaratGameId: $game->id);

    expect($game->isTie())->toBeTrue()
        ->and(Transaction::count())->toBe(6)
        ->and(BaccaratPayoutDeposited::count())->toBe(6);

});


test('verifies payout processing for cancel result in Dragon Tiger game', function () {

    $game = BaccaratTestHelper::createGameIncludeWinAndLoseTickets();
    $game->winner = 'cancel';
    $game->saveQuietly();
    $game->refresh();

    (new BaccaratPayoutProcessingManagerAction())(baccaratGameId: $game->id);

    expect($game->isCancel())->toBeTrue()
        ->and(Transaction::count())->toBe(10)
        ->and(BaccaratPayoutDeposited::count())->toBe(10);

});
