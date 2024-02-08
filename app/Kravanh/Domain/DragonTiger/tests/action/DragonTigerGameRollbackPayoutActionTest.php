<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetDepositedPayoutForRollbackAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameRollbackPayoutAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerPayoutProcessingManagerAction;
use App\Kravanh\Domain\DragonTiger\tests\DragonTigerTestHelper;
use App\Kravanh\Support\LockHelper;
use Bavix\Wallet\Models\Transaction;

test('verifies successful rollback of payouts in Dragon Tiger game', function () {

    $game = DragonTigerTestHelper::createGameIncludeWinAndLoseTickets();

    (new DragonTigerPayoutProcessingManagerAction())(dragonTigerGameId: $game->id);

    $payouts = (new DragonTigerGameGetDepositedPayoutForRollbackAction())($game->id);
    $firstPayout = $payouts->first();

    (new DragonTigerGameRollbackPayoutAction())(payouts: $payouts);

    expect($payouts->count())->toBe(4);

    $payouts = (new DragonTigerGameGetDepositedPayoutForRollbackAction())($game->id);

    $meta = Transaction::whereType('withdraw')->first()->meta;

    expect($payouts->count())->toBe(0)
        ->and(Transaction::count())->toBe(8)
        ->and(Transaction::where('type', 'withdraw')->count())->toBe(4)
        ->and($meta['game'])->toBe('dragon_tiger')
        ->and($meta['mode'])->toBe('company')
        ->and($meta['note'])->toBe('rollback payout')
        ->and($meta['type'])->toBe('withdraw')
        ->and($meta['currency'])->toBe($firstPayout->member->currency->value)
        ->and($meta['match_id'])->toBe($firstPayout->dragon_tiger_game_id)
        ->and($meta['current_balance'])->toBe(0)
        ->and($meta['before_balance'])->toBe((int)$firstPayout->amount);

});

test('ensures proper handling of failed payout rollback in Dragon Tiger game', function () {

    $game = DragonTigerTestHelper::createGameIncludeWinAndLoseTickets();

    (new DragonTigerPayoutProcessingManagerAction())(dragonTigerGameId: $game->id);

    $payouts = (new DragonTigerGameGetDepositedPayoutForRollbackAction())($game->id);

    $secondPayoutMemberId = $payouts->toArray()[1]['member_id'];

    //make it throw exception
    $locker = LockHelper::lockWallet($secondPayoutMemberId);
    $locker->block(1000);

    (new DragonTigerGameRollbackPayoutAction())(payouts: $payouts);

    $locker->release();

    expect($payouts->count())->toBe(4)
        ->and(Transaction::count())->toBe(7) // deposit: 4, withdraw: 3
        ->and(Transaction::whereType('withdraw')->count())->toBe(3);

});
