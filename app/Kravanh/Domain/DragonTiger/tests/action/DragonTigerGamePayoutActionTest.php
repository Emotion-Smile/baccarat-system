<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetWinningTicketsAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGamePayoutAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameTransactionTicketPayoutMetaData;
use App\Kravanh\Domain\DragonTiger\tests\DragonTigerTestHelper;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\LockHelper;
use Bavix\Wallet\Models\Transaction;

test('test successful deposit payout for winning tickets in dragon tiger game', function () {

    $game = DragonTigerTestHelper::createGameIncludeWinAndLoseTickets();

    $tickets = (new DragonTigerGameGetWinningTicketsAction())($game);

    $transaction = (new DragonTigerGamePayoutAction())(game: $game, tickets: $tickets);

    $meta = $transaction->map(fn($tx) => DragonTigerGameTransactionTicketPayoutMetaData::fromMeta($tx->meta));

    expect($meta->count())->toBe($tickets->count())
        ->and(Transaction::pluck('amount')->toArray())->toBe($tickets->pluck('payout')->toArray())
        ->and(Transaction::pluck('payable_type')->unique()->toArray())->toMatchArray([Member::class])
        ->and(DragonTigerGameTransactionTicketPayoutMetaData::fromMeta(Transaction::first()->meta)->toMeta())->toBe($meta->first()->toMeta());

});

test('test deposit payout skipped on failed wallet lock for dragon tiger game', function () {

    $game = DragonTigerTestHelper::createGameIncludeWinAndLoseTickets();

    $tickets = (new DragonTigerGameGetWinningTicketsAction())($game);
    $thirdUserId = $tickets[2]->user_id;

    //failed 1 deposit
    $locker = LockHelper::lockWallet($thirdUserId);
    $locker->block(10000);

    $transactions = (new DragonTigerGamePayoutAction())(
        game: $game,
        tickets: $tickets
    );

    expect($transactions->count())->toBe($tickets->count() - 1)
        ->and(Transaction::where('payable_id', $thirdUserId)->first())->toBeNull();

});
