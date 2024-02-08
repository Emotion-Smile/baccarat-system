<?php

use App\Kravanh\Domain\User\Models\Agent;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\WalletBackup\Actions\RemoveDuplicateWalletAction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

test('it can remove duplicate wallet', function () {

    Member::factory()->count(2)->create()->each(fn($member) => $member->balanceInt);
    Agent::factory()->count(2)->create()->each(fn($agent) => $agent->balanceInt);

    User::where('type', 'member')->get()->each(fn($member) => $member->deposit(100));
    User::where('type', 'agent')->get()->each(fn($member) => $member->deposit(100));

    $holderType = ['App\\Kravanh\\Domain\\User\\Models\\Agent', 'App\\Kravanh\\Domain\\User\\Models\\Member'];

    $correctWallets = DB::table('wallets')
        ->whereIn('holder_type', $holderType)
        ->count();

    expect($correctWallets)->toBe(4);

    $duplicateCount = (new RemoveDuplicateWalletAction())();

    $correctWallets = DB::table('wallets')
        ->whereIn('holder_type', $holderType)
        ->count();

    expect($duplicateCount)->toBe(4)
        ->and($correctWallets)->toBe(4);

    $duplicateCount = (new RemoveDuplicateWalletAction())();

    expect($duplicateCount)->toBe(0);
})->group('wallet-backup');
