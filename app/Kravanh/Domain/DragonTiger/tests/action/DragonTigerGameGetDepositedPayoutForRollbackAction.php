<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetDepositedPayoutForRollbackAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerPayoutProcessingManagerAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerPayoutDeposited;
use App\Kravanh\Domain\DragonTiger\tests\DragonTigerTestHelper;
use App\Kravanh\Domain\User\Models\Member;

test('verifies retrieval of correct transactions for payout rollback in Dragon Tiger game', function () {

    $game = DragonTigerTestHelper::createGameIncludeWinAndLoseTickets();

    (new DragonTigerPayoutProcessingManagerAction())(dragonTigerGameId: $game->id);

    $payouts = (new DragonTigerGameGetDepositedPayoutForRollbackAction())($game->id);

    expect($payouts->count())->toBe(4)
        ->and($payouts->first()->toArray())->toHaveKeys(['id', 'member_id', 'amount', 'member', 'dragon_tiger_game_id'])
        ->and($payouts->first()->member)->toBeInstanceOf(Member::class);

    DragonTigerPayoutDeposited::query()
        ->where('dragon_tiger_game_id', $game->id)
        ->update(['rollback_transaction_id' => 1]);

    $payouts = (new DragonTigerGameGetDepositedPayoutForRollbackAction())($game->id);
    expect($payouts->count())->toBe(0);

});
