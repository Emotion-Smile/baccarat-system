<?php

use App\Kravanh\Domain\WalletBackup\Actions\GetWalletHaveDifferentBalanceValueAction;
use App\Kravanh\Domain\WalletBackup\Models\WalletBackup;

test('it can get wallet have different balance value', function () {
    WalletBackup::insert([
        [
            'wallet_id' => 1,
            'holder_id' => 1,
            'holder_type' => 'App\Kravanh\Domain\User\Models\Member',
            'balance' => 1000,
            'cache_balance' => 100,
            'last_updated_balance' => now()->format('Ym'),
            'is_cache_balance_updated' => true
        ],
        [
            'wallet_id' => 2,
            'holder_id' => 2,
            'holder_type' => 'App\Kravanh\Domain\User\Models\Member',
            'balance' => 900,
            'cache_balance' => 90,
            'last_updated_balance' => now()->format('Ym'),
            'is_cache_balance_updated' => true
        ],
        [

            'wallet_id' => 3,
            'holder_id' => 3,
            'holder_type' => 'App\Kravanh\Domain\User\Models\Member',
            'balance' => 900,
            'cache_balance' => 900,
            'last_updated_balance' => now()->format('Ym'),
            'is_cache_balance_updated' => true
        ]
    ]);

    expect(WalletBackup::count())->toBe(3);

    $wallets = (new GetWalletHaveDifferentBalanceValueAction())();

    expect($wallets->count())->toBe(2);

    $wallets->each(fn($wallet) => expect($wallet->balance)->not()->toBe($wallet)->cache_balance);
})->group('wallet-backup');
