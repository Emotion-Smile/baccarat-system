<?php


use App\Kravanh\Domain\Match\Actions\PayoutDepositedCreateAction;
use App\Kravanh\Domain\Match\Models\PayoutDeposit;

test('it can create payout deposited recorded', closure: function () {

    $depositMeta = [
        'match_id' => 1,
        'action' => 'DepositPayoutToMember'
    ];

    (new PayoutDepositedCreateAction())(
        matchId: $depositMeta['match_id'],
        memberId: 1,
        transactionId: 11,
        depositor: 'test_deposit'
    );

    $payoutDeposit = PayoutDeposit::first();

    expect(PayoutDeposit::count())->toBe(1)
        ->and($payoutDeposit->match_id)->toBe($payoutDeposit['match_id'])
        ->and($payoutDeposit->member_id)->toBe(1)
        ->and($payoutDeposit->transaction_id)->toBe(11)
        ->and($payoutDeposit->depositor)->toBe('test_deposit');
});


