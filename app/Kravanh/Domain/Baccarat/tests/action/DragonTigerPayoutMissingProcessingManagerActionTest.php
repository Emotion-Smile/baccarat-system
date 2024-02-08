<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetWinningTicketsAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerPayoutMissingProcessingManagerAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerPayoutProcessingManagerAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerPayoutDeposited;
use App\Kravanh\Domain\DragonTiger\tests\DragonTigerTestHelper;
use App\Kravanh\Domain\Match\Events\AllPayoutDeposited;
use App\Kravanh\Support\LockHelper;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Support\Facades\Event;

test('ensures recovery of missing payouts in Dragon Tiger game', function () {


    $game = DragonTigerTestHelper::createGameIncludeWinAndLoseTickets();

    $tickets = (new DragonTigerGameGetWinningTicketsAction())($game);
    $thirdUserId = $tickets[2]->user_id;

    //failed 1 deposit
    $locker = LockHelper::lockWallet($thirdUserId);
    $locker->block(10000);

    (new DragonTigerPayoutProcessingManagerAction())(dragonTigerGameId: $game->id);

    $locker->release();

    expect(Transaction::count())->toBe(3)
        ->and(DragonTigerPayoutDeposited::count())->toBe(3)
        ->and(Transaction::where('payable_id', $thirdUserId)->first())->toBeNull();

    Event::fake();

    (new DragonTigerPayoutMissingProcessingManagerAction())(dragonTigerGameId: $game->id);

    expect(Transaction::count())->toBe(4)
        ->and(DragonTigerPayoutDeposited::count())->toBe(4)
        ->and(Transaction::where('payable_id', $thirdUserId)->first())->not()->toBeNull();

    Event::assertDispatched(AllPayoutDeposited::class);


});
