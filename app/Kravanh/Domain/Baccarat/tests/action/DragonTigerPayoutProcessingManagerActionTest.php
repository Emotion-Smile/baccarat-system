<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerPayoutProcessingManagerAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerPayoutDeposited;
use App\Kravanh\Domain\DragonTiger\tests\DragonTigerTestHelper;
use App\Kravanh\Domain\Match\Events\AllPayoutDeposited;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Support\Facades\Event;

test('verifies regular payout processing in Dragon Tiger game', function () {

    Event::fake();

    $game = DragonTigerTestHelper::createGameIncludeWinAndLoseTickets();

    (new DragonTigerPayoutProcessingManagerAction())(dragonTigerGameId: $game->id);

    Event::assertDispatched(AllPayoutDeposited::class);

    expect($game->isTie())->toBeFalse()
        ->and(Transaction::count())->toBe(4)
        ->and(DragonTigerPayoutDeposited::count())->toBe(4);


});

test('verifies payout processing for tie result in Dragon Tiger game', function () {

    $game = DragonTigerTestHelper::createGameIncludeWinAndLoseTickets();
    $game->winner = 'tie';
    $game->saveQuietly();
    $game->refresh();

    (new DragonTigerPayoutProcessingManagerAction())(dragonTigerGameId: $game->id);

    expect($game->isTie())->toBeTrue()
        ->and(Transaction::count())->toBe(6)
        ->and(DragonTigerPayoutDeposited::count())->toBe(6);

});


test('verifies payout processing for cancel result in Dragon Tiger game', function () {

    $game = DragonTigerTestHelper::createGameIncludeWinAndLoseTickets();
    $game->winner = 'cancel';
    $game->saveQuietly();
    $game->refresh();

    (new DragonTigerPayoutProcessingManagerAction())(dragonTigerGameId: $game->id);

    expect($game->isCancel())->toBeTrue()
        ->and(Transaction::count())->toBe(10)
        ->and(DragonTigerPayoutDeposited::count())->toBe(10);

});
