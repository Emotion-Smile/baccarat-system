<?php

use App\Kravanh\Domain\User\Models\Agent;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\WalletBackup\Actions\GetWalletBackupAction;
use App\Kravanh\Domain\WalletBackup\Actions\WalletBackupAction;

test('it can get correct wallet backup', function () {
    $members = Member::factory()
        ->count(10)
        ->create();

    $members->each(fn($member) => $member->deposit(1000));

    $agent = Agent::firstWhere('type', 'agent');
    $agent->balanceInt;

    (new WalletBackupAction())();

    $member = Member::firstWhere('type', 'member');
    $memberWallet = (new GetWalletBackupAction())($member);
    expect($memberWallet->holder_type)->toBe(get_class($member));
    
    $agentWallet = (new GetWalletBackupAction())($agent);
    expect($agentWallet->holder_type)->toBe(get_class($agent));

});
