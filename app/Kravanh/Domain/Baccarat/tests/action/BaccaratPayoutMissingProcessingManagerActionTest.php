<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetWinningTicketsAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratPayoutMissingProcessingManagerAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratPayoutProcessingManagerAction;
use App\Kravanh\Domain\Baccarat\Models\BaccaratPayoutDeposited;
use App\Kravanh\Domain\Baccarat\tests\BaccaratTestHelper;
use App\Kravanh\Domain\Match\Events\AllPayoutDeposited;
use App\Kravanh\Support\LockHelper;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Support\Facades\Event;

test('ensures recovery of missing payouts in Dragon Tiger game', function () {


    $game = BaccaratTestHelper::createGameIncludeWinAndLoseTickets();

    $tickets = (new BaccaratGameGetWinningTicketsAction())($game);
    $thirdUserId = $tickets[2]->user_id;

    //failed 1 deposit
    $locker = LockHelper::lockWallet($thirdUserId);
    $locker->block(10000);

    (new BaccaratPayoutProcessingManagerAction())(baccaratGameId: $game->id);

    $locker->release();

    expect(Transaction::count())->toBe(3)
        ->and(BaccaratPayoutDeposited::count())->toBe(3)
        ->and(Transaction::where('payable_id', $thirdUserId)->first())->toBeNull();

    Event::fake();

    (new BaccaratPayoutMissingProcessingManagerAction())(baccaratGameId: $game->id);

    expect(Transaction::count())->toBe(4)
        ->and(BaccaratPayoutDeposited::count())->toBe(4)
        ->and(Transaction::where('payable_id', $thirdUserId)->first())->not()->toBeNull();

    Event::assertDispatched(AllPayoutDeposited::class);


});
