<?php

use App\Kravanh\Domain\Match\Actions\PayoutDepositedCreateAction;
use App\Kravanh\Domain\Match\Actions\RollbackPayoutAction;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Models\PayoutDeposit;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Bavix\Wallet\Interfaces\Storable;
use Bavix\Wallet\Models\Transaction;


test('it can rollback payout transaction v1', function () {
    app(Storable::class)->fresh();

    $match = Matches::factory()->createQuietly();

    $matchId = $match->id;
    $depositAmount = 10000;

    User::factory(['type' => UserType::MEMBER])->count(10)->create();

    // Fake tx
    Member::all()
        ->each(function (Member $member) use ($matchId, $depositAmount) {
            $tx = $member->deposit($depositAmount, [
                'match_id' => $matchId,
                'fight_number' => 1,
                'type' => 'payout'
            ]);

            (new PayoutDepositedCreateAction())(
                matchId: $matchId,
                memberId: $member->id,
                transactionId: $tx->id,
                depositor: 'deposit'
            );

        })->each(fn(Member $member) => expect($member->balanceInt)->toBe($depositAmount));


    expect(Transaction::count())->toBe(11)
        ->and(PayoutDeposit::count())->toBe(11);

    PayoutDeposit::all()
        ->each(fn($payout) => expect($payout->depositor)->toBe('deposit'));

    expect((new RollbackPayoutAction())($matchId, 'test rollback'))->toBe(11);

    PayoutDeposit::all()
        ->each(fn($payout) => expect($payout->depositor)->toBe('rollback'));

    expect((new RollbackPayoutAction())($matchId, 'test rollback'))->toBe(0);


    Member::all()->each(fn($member) => expect($member->balanceInt)->toBe(0));

    expect(Transaction::count())->toBe(22)
        ->and(Transaction::query()
            ->where('type', 'withdraw')
            ->where('meta->action', 'modify_match')
            ->where('meta->note', 'test rollback')
            ->count())
        ->toBe(11);
});
