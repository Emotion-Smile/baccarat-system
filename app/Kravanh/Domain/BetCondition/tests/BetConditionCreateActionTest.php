<?php

use App\Kravanh\Domain\BetCondition\Actions\BetConditionCreateAction;
use App\Kravanh\Domain\BetCondition\Models\BetCondition;
use function Pest\Laravel\assertDatabaseCount;

test('it can create or update user bet condition', closure: function () {

    (new BetConditionCreateAction)(
        groupId: 1,
        userId: 2,
        minBetPerTicket: 2,
        maxBetPerTicket: 200,
        matchLimit: 400,
        WinLimitPerDay: 500
    );

    assertDatabaseCount('bet_conditions', 1);
    $condition = BetCondition::getCondition(groupId: 1, userId: 2);

    expect($condition->minBetPerTicket)->toBe(2)
        ->and($condition->maxBetPerTicket)->toBe(200)
        ->and($condition->matchLimit)->toBe(400)
        ->and($condition->winLimitPerDay)->toBe(500)
        ->and($condition->force)->toBeFalse();


    (new BetConditionCreateAction)(
        groupId: 1,
        userId: 2,
        minBetPerTicket: 3,
        maxBetPerTicket: 300,
        matchLimit: 500,
        WinLimitPerDay: 1000,
        force: true
    );

    assertDatabaseCount('bet_conditions', 1);
    $condition = BetCondition::getCondition(groupId: 1, userId: 2);


    expect($condition->minBetPerTicket)->toBe(3)
        ->and($condition->maxBetPerTicket)->toBe(300)
        ->and($condition->matchLimit)->toBe(500)
        ->and($condition->winLimitPerDay)->toBe(1000)
        ->and($condition->force)->toBeTrue();
});
