<?php

use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\WalletBackup\Actions\GetWalletBackupAction;
use App\Kravanh\Domain\WalletBackup\Actions\IsWalletHasCacheBalanceAction;
use App\Kravanh\Domain\WalletBackup\Actions\WalletBackupAction;
use App\Kravanh\Domain\WalletBackup\Models\WalletBackup;

beforeEach(function () {
    Member::factory()
        ->count(10)
        ->create()
        ->each(fn($member) => $member->balanceInt);

    (new WalletBackupAction())();
});

test('The wallet has not updated the cache balance', function () {

    $member = Member::firstWhere('type', 'member');

    $isHas = (new IsWalletHasCacheBalanceAction())(
        $member->id,
        get_class($member)
    );

    expect($isHas)->toBeFalse();

})->group('wallet-backup');

test('The wallet already updated the cache balance', closure: function () {

    $member = Member::firstWhere('type', 'member');

    WalletBackup::query()
        ->where('holder_id', $member->id)
        ->where('holder_type', get_class($member))
        ->update([
            'is_cache_balance_updated' => 1
        ]);

    $isHas = (new IsWalletHasCacheBalanceAction())(
        $member->id,
        get_class($member)
    );

    expect($isHas)->toBeTrue();

})->group('wallet-backup');

test('The wallet do not exist', function () {

    $member = Member::firstWhere('type', 'member');
    $wallet = (new GetWalletBackupAction())($member);
    $wallet->delete();
    
    $isHas = (new IsWalletHasCacheBalanceAction())(
        $member->id,
        get_class($member)
    );

    expect($isHas)->toBeNull();

})->group('wallet-backup');
