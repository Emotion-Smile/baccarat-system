<?php

use App\Kravanh\Domain\User\Models\Agent;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\WalletBackup\Actions\GetHolderIdDuplicateWalletAction;
use App\Models\User;

test('it can get holder_id duplicate wallet', function () {

    Member::factory()->count(2)->create()->each(fn($member) => $member->balanceInt);
    Agent::factory()->count(2)->create()->each(fn($agent) => $agent->balanceInt);

    User::where('type', 'member')->get()->each(fn($member) => $member->deposit(100));
    User::where('type', 'agent')->get()->each(fn($member) => $member->deposit(100));

    $holderIds = (new GetHolderIdDuplicateWalletAction())();

    expect($holderIds->count())->toBe(4);
})->group('wallet-backup');


