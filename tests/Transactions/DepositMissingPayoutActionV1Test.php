<?php

use App\Kravanh\Domain\Match\Actions\DepositMissingPayoutV1Action;
use App\Kravanh\Domain\Match\Models\BetRecord;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Bavix\Wallet\Interfaces\Storable;
use Bavix\Wallet\Models\Transaction;

test(/**
 * @throws Throwable
 */ /**
 * @throws Throwable
 */ 'it can deposit to missing payout v1', function () {
    app(Storable::class)->fresh();

    $match = Matches::factory(['result' => MatchResult::WALA])->createQuietly();

    $matchId = $match->id;

    //total member 11
    User::factory(['type' => UserType::MEMBER])->count(10)->create();

    Member::whereType(UserType::MEMBER)
        ->get()
        ->each(function (Member $member) use ($matchId) {
            $depositAmount = 1000;

            //0.09
            $bet = BetRecord::factory([
                'user_id' => $member->id,
                'amount' => 1000,
                'payout' => 900
            ])->createQuietly();

            // exclude 3 members
            if ($member->id < 5) {
                return;
            }

            $member->deposit($depositAmount, [
                'bet_id' => $bet->id,
                'match_id' => $matchId,
                'fight_number' => 1,
                'type' => 'payout'
            ]);

            expect($member->getCurrentBalance())->toBe($depositAmount);
        });

    expect(
        Transaction::query()
            ->where('meta->match_id', $match->id)
            ->where('meta->type', 'payout')
            ->count()
    )->toBe(7);

    (new DepositMissingPayoutV1Action())($matchId);
    expect(Transaction::count())->toBe(10);


    (new DepositMissingPayoutV1Action())($matchId);
    expect(Transaction::count())->toBe(10);

});
