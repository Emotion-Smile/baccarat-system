<?php


use App\Kravanh\Domain\User\Models\Agent;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\WalletBackup\Actions\GetDuplicationWalletAction;
use App\Kravanh\Domain\WalletBackup\Actions\GetHolderIdDuplicateWalletAction;
use App\Models\User;


test('it can get duplicate wallets', function () {

    Member::factory()->count(2)->create()->each(fn($member) => $member->balanceInt);
    Agent::factory()->count(2)->create()->each(fn($agent) => $agent->balanceInt);

    User::where('type', 'member')->get()->each(fn($member) => $member->deposit(100));
    User::where('type', 'agent')->get()->each(fn($member) => $member->deposit(100));

    $holderIds = (new GetHolderIdDuplicateWalletAction())();
    $wallets = (new GetDuplicationWalletAction())(holderIds: $holderIds->toArray());

    expect($wallets->count())->toBe(8)
        ->and((object)$wallets->first())
        ->toHaveProperties([
            'holder_id',
            'holder_type',
            'type'
        ]);


})->group('wallet-backup');

test('it return collection empty if holder ids is empty', function () {

    $holderIds = (new GetHolderIdDuplicateWalletAction())();
    $wallets = (new GetDuplicationWalletAction())(holderIds: $holderIds->toArray());

    expect($wallets->isEmpty())->toBeTrue();
})->group('wallet-backup');
