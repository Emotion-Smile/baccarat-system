<?php


use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\WalletBackup\Actions\WalletBackupAction;
use App\Kravanh\Domain\WalletBackup\Models\WalletBackup;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

test('it can copy data from wallet table to wallet backup table', function () {

    Member::factory()
        ->count(10)
        ->create()
        ->each(fn($member) => $member->balanceInt);

    (new WalletBackupAction())();

    expect(DB::table('wallets')->count())->toBe(10)
        ->and(DB::table('wallet_backups')->count())->toBe(10);

    DB::table('wallets')->get()
        ->each(function ($wallet) {
            $walletBackup = WalletBackup::firstWhere('wallet_id', $wallet->id);
            expect($wallet->balance)->toBe($walletBackup->balance)
                ->and($wallet->holder_id)->toBe($walletBackup->holder_id)
                ->and($wallet->holder_type)->toBe($walletBackup->holder_type)
                ->and((int)Date::createFromTimeString($wallet->updated_at)->format('Ym'))->toBe($walletBackup->last_updated_balance)
                ->and((int)$walletBackup->cache_balance)->toBe(0)
                ->and($walletBackup->is_cache_balance_updated)->toBeFalse();
        }
        );
})->group('wallet-backup');
