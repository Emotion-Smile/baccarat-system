<?php

use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\WalletBackup\Actions\GetWalletBackupAction;
use App\Kravanh\Domain\WalletBackup\Actions\UpdateCacheBalanceAction;
use App\Kravanh\Domain\WalletBackup\Actions\WalletBackupAction;

test('it can update cache balance', function () {

    $members = Member::factory()
        ->count(10)
        ->create();

    $members->each(fn($member) => $member->deposit(1000));


    (new WalletBackupAction())();

    $members->each(function ($member) {

        (new UpdateCacheBalanceAction())(
            $member->id,
            get_class($member),
            $member->balanceInt
        );

    });

    $members->each(function ($member) {

        $cacheBalance = (new GetWalletBackupAction())($member);

        expect($member->balanceInt)->toBe((int)$cacheBalance->cache_balance)
            ->and((boolean)$cacheBalance->is_cache_balance_updated)->toBeTrue();

    });

})->group('wallet-backup');
