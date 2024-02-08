<?php


use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\WalletBackup\Actions\SyncBalanceAction;
use Bavix\Wallet\Models\Wallet;

test('it can sync balance', function () {

    $member = Member::factory()->create();
    $member->deposit(1000);
    expect($member->balanceInt)->toBe(1000);

    (new SyncBalanceAction())($member->wallet->id, 2000);

    expect(Wallet::find($member->wallet->id)->balance)->toBe('2000')
        ->and($member->refresh()->balanceInt)->toBe(2000);

})->group('wallet-backup');
