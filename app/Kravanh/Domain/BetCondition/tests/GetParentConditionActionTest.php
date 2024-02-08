<?php

use App\Kravanh\Domain\BetCondition\Actions\GetParentBetConditionAction;
use App\Kravanh\Domain\User\Models\Member;

test('it can get parent bet condition of member', function () {
    $member = Member::factory()->create();

    $defaultCondition = (new GetParentBetConditionAction())(
        groupId: 1,
        parentId: $member->agent,
    );

    expect($defaultCondition->minBetPerTicket)->toBe(1000)
        ->and($defaultCondition->maxBetPerTicket)->toBe(10000)
        ->and($defaultCondition->matchLimit)->toBe(10000)
        ->and($defaultCondition->winLimitPerDay)->toBe(20000);

});

test('it can get parent bet condition of agent', function () {
    $member = Member::factory()->create();

    $defaultCondition = (new GetParentBetConditionAction())(
        groupId: 1,
        parentId: $member->master_agent,
    );

    expect($defaultCondition->minBetPerTicket)->toBe(1000)
        ->and($defaultCondition->maxBetPerTicket)->toBe(10000)
        ->and($defaultCondition->matchLimit)->toBe(10000)
        ->and($defaultCondition->winLimitPerDay)->toBe(20000);

});

