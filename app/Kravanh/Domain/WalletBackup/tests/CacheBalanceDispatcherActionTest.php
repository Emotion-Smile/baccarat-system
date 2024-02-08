<?php

use App\Kravanh\Domain\User\Models\Agent;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\WalletBackup\Actions\UpdateCacheBalanceDispatcherAction;
use App\Kravanh\Domain\WalletBackup\Actions\WalletBackupAction;
use App\Kravanh\Domain\WalletBackup\Jobs\UpdateCacheBalanceJob;
use App\Kravanh\Domain\WalletBackup\Models\WalletBackup;
use Illuminate\Support\Facades\Bus;

beforeEach(function () {
    Member::factory()
        ->count(10)
        ->create()
        ->each(fn ($member) => $member->deposit(1000));

    Agent::factory()
        ->count(6)
        ->create()
        ->each(fn ($member) => $member->deposit(500));

    (new WalletBackupAction())();
});

test('it can dispatch count correct', function () {
    Bus::fake();
    (new UpdateCacheBalanceDispatcherAction())(start: 202301, end: 202412, chunkSize: 2);
    Bus::assertDispatched(UpdateCacheBalanceJob::class, 8);
})->group('wallet-backup');

test('it can dispatch job', function () {

    (new UpdateCacheBalanceDispatcherAction())(start: 202301, end: 202412);

    WalletBackup::all()
        ->each(fn ($wallet) => expect($wallet->cache_balance)->toBe($wallet->balance));
})->group('wallet-backup');
