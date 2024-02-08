<?php

use App\Kravanh\Domain\User\Models\Agent;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\WalletBackup\Actions\GetUserBalanceAction;

test('it can get user current balance', function () {
    $member = Member::factory()->create();

    $member->deposit(100);

    $balance = (new GetUserBalanceAction())(get_class($member), $member->id);
    expect($balance)->toBe(100);


    $agent = Agent::factory()->create();
    $agent->deposit(300);
    $balance = (new GetUserBalanceAction())(get_class($agent), $agent->id);
    expect($balance)->toBe(300);
})->group('wallet-backup');
