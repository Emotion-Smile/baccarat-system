<?php

use App\Kravanh\Domain\Match\Models\BetRecord;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\BetOn;
use App\Kravanh\Domain\User\Actions\GetMemberWinLoseTodayAction;
use App\Kravanh\Domain\User\Models\Member;

test('it can get member win lose today (member lose)', function () {

    Matches::factory(
        [
            'payout_meron' => 90,
            'result' => BetOn::MERON
        ]
    )->create();

    $member = Member::factory()->create();

    BetRecord::factory([
        'user_id' => $member->id,
        'bet_on' => BetOn::MERON,
        'amount' => 1000
    ])
        ->count(2)
        ->create();

    BetRecord::factory([
        'user_id' => $member->id,
        'bet_on' => BetOn::WALA,
        'amount' => 1000
    ])
        ->count(2)
        ->create();

    $totalWinLose = (new GetMemberWinLoseTodayAction())($member->id);

    $winLimit = 0;

    expect($totalWinLose)->toBe(-200)
        ->and($winLimit)->toBeGreaterThan($totalWinLose);

});

test('it can get member win lose today (member win)', function () {

    Matches::factory(
        [
            'payout_meron' => 90,
            'result' => BetOn::MERON
        ]
    )->create();

    $member = Member::factory()->create();

    BetRecord::factory([
        'user_id' => $member->id,
        'bet_on' => BetOn::MERON,
        'amount' => 1000
    ])
        ->count(2)
        ->create();

    $totalWinLose = (new GetMemberWinLoseTodayAction())($member->id);

    $winLimit = 1000;

    //ban
    expect($totalWinLose)->toBe(1800)
        ->and($winLimit)->toBeLessThan($totalWinLose);

});

test('it can handle first bet of today', function () {

    $member = Member::factory()->create();
    $totalWinLose = (new GetMemberWinLoseTodayAction())($member->id);

    expect($totalWinLose)->toBe(0);
});
