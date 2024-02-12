<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetWinningTicketsAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGamePayoutAction;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameTransactionTicketPayoutMetaData;
use App\Kravanh\Domain\Baccarat\tests\BaccaratTestHelper;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\LockHelper;
use Bavix\Wallet\Models\Transaction;

test('test successful deposit payout for winning tickets in dragon tiger game', function () {

    $game = BaccaratTestHelper::createGameIncludeWinAndLoseTickets();

    $tickets = (new BaccaratGameGetWinningTicketsAction())($game);

    $transaction = (new BaccaratGamePayoutAction())(game: $game, tickets: $tickets);

    $meta = $transaction->map(fn($tx) => BaccaratGameTransactionTicketPayoutMetaData::fromMeta($tx->meta));

    expect($meta->count())->toBe($tickets->count())
        ->and(Transaction::pluck('amount')->toArray())->toBe($tickets->pluck('payout')->toArray())
        ->and(Transaction::pluck('payable_type')->unique()->toArray())->toMatchArray([Member::class])
        ->and(BaccaratGameTransactionTicketPayoutMetaData::fromMeta(Transaction::first()->meta)->toMeta())->toBe($meta->first()->toMeta());

});

test('test deposit payout skipped on failed wallet lock for dragon tiger game', function () {

    $game = BaccaratTestHelper::createGameIncludeWinAndLoseTickets();

    $tickets = (new BaccaratGameGetWinningTicketsAction())($game);
    $thirdUserId = $tickets[2]->user_id;

    //failed 1 deposit
    $locker = LockHelper::lockWallet($thirdUserId);
    $locker->block(10000);

    $transactions = (new BaccaratGamePayoutAction())(
        game: $game,
        tickets: $tickets
    );

    expect($transactions->count())->toBe($tickets->count() - 1)
        ->and(Transaction::where('payable_id', $thirdUserId)->first())->toBeNull();

});
