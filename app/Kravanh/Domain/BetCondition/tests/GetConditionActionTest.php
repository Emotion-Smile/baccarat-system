<?php

use App\Kravanh\Domain\BetCondition\Actions\BetConditionCreateAction;
use App\Kravanh\Domain\BetCondition\Actions\GetBetConditionAction;
use App\Kravanh\Domain\User\Models\Member;

test('it should return default bet condition', function () {
    $member = Member::factory()->create();

    $defaultCondition = (new GetBetConditionAction())(
        groupId: 1,
        memberId: $member->id,
        agentId: $member->agent
    );

    expect($defaultCondition->minBetPerTicket)->toBe(1000)
        ->and($defaultCondition->maxBetPerTicket)->toBe(10000)
        ->and($defaultCondition->matchLimit)->toBe(10000)
        ->and($defaultCondition->winLimitPerDay)->toBe(20000);


});

test('it should return member bet condition belong to group', function () {

    $member = Member::factory()->create();

    // Member condition
    (new BetConditionCreateAction())(
        groupId: 1,
        userId: $member->id,
        minBetPerTicket: 3,
        maxBetPerTicket: 300,
        matchLimit: 500,
        WinLimitPerDay: 1000
    );

    $memberCondition = (new GetBetConditionAction())(
        groupId: 1,
        memberId: $member->id,
        agentId: $member->agent
    );

    expect($memberCondition->minBetPerTicket)->toBe(3)
        ->and($memberCondition->maxBetPerTicket)->toBe(300)
        ->and($memberCondition->matchLimit)->toBe(500)
        ->and($memberCondition->winLimitPerDay)->toBe(1000)
        ->and($memberCondition->force)->toBeFalse();
});

test('it should return agent bet condition belong to group with default member condition', function () {
    $member = Member::factory()->create();

    // agent condition
    (new BetConditionCreateAction())(
        groupId: 1,
        userId: $member->agent,
        minBetPerTicket: 3,
        maxBetPerTicket: 300,
        matchLimit: 500,
        WinLimitPerDay: 1000,
    );

    $agentCondition = (new GetBetConditionAction())(
        groupId: 1,
        memberId: $member->id,
        agentId: $member->agent
    );

    expect($agentCondition->minBetPerTicket)->toBe(3)
        ->and($agentCondition->maxBetPerTicket)->toBe(300)
        ->and($agentCondition->matchLimit)->toBe(500)
        ->and($agentCondition->winLimitPerDay)->toBe($member->condition['credit_limit'])
        ->and($agentCondition->force)->toBeFalse();
});

test('it should always return agent condition', function () {
    $member = Member::factory()->create();

    // Member condition
    (new BetConditionCreateAction())(
        groupId: 1,
        userId: $member->agent,
        minBetPerTicket: 3,
        maxBetPerTicket: 300,
        matchLimit: 500,
        WinLimitPerDay: 1000,
        force: true
    );

    $agentCondition = (new GetBetConditionAction())(
        groupId: 1,
        memberId: $member->id,
        agentId: $member->agent
    );

    expect($agentCondition->minBetPerTicket)->toBe(3)
        ->and($agentCondition->maxBetPerTicket)->toBe(300)
        ->and($agentCondition->matchLimit)->toBe(500)
        ->and($agentCondition->winLimitPerDay)->toBe(1000)
        ->and($agentCondition->force)->toBeTrue();
});
