<?php

use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\WalletBackup\Actions\GetWalletBackupAction;
use App\Kravanh\Domain\WalletBackup\Actions\UpdateLoginBalanceAction;
use App\Kravanh\Domain\WalletBackup\Actions\WalletBackupAction;

test('it can update login balance', function () {

    $members = Member::factory()
        ->count(10)
        ->create();

    $members->each(fn($member) => $member->deposit(1000));


    (new WalletBackupAction())();

    $members->each(function ($member) {

        (new UpdateLoginBalanceAction())(
            $member->id,
            get_class($member),
            $member->balanceInt
        );

    });

    $members->each(function ($member) {

        $wallet = (new GetWalletBackupAction())(holder: $member);

        expect($member->balanceInt)->toBe((int)$wallet->login_balance)
            ->and((boolean)$wallet->is_cache_balance_updated)->toBeTrue()
            ->and((int)$wallet->cache_balance)->toBe(0);

    });

})->group('wallet-backup');
