<?php

use App\Kravanh\Domain\Match\Actions\ModifyMatchResultAction;
use App\Kravanh\Domain\Match\Actions\PayoutDepositedCreateAction;
use App\Kravanh\Domain\Match\Events\MatchResultUpdated;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Bavix\Wallet\Interfaces\Storable;
use Illuminate\Support\Facades\Event;

function fakeMatchTransactions(int $result, bool $isMatchEnded = true): Matches
{
    //create transaction
    app(Storable::class)->fresh();

    $match = Matches::factory([
        'result' => $result,
        'match_end_at' => $isMatchEnded ? now() : null
    ])->createQuietly();

    $matchId = $match->id;

    User::factory(['type' => UserType::MEMBER])->count(10)->create();

    // Fake tx
    Member::all()
        ->each(function (Member $member) use ($matchId) {

            $depositAmount = 10000;

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

        });

    return $match;
}


test('it can not modify match result if match not yet ended', function () {

    $match = fakeMatchTransactions(MatchResult::CANCEL, isMatchEnded: false);


    $txRollbackCount = app(ModifyMatchResultAction::class)(
        match: $match,
        result: MatchResult::DRAW,
        note: 'test'
    );


    expect($txRollbackCount)->toBe(0);

});


test('it can modify match result from cancel to draw correctly', closure: function () {

    Event::fake();

    $match = fakeMatchTransactions(MatchResult::CANCEL);


    $txRollbackCount = app(ModifyMatchResultAction::class)(
        match: $match,
        result: MatchResult::DRAW,
        note: 'test'
    );


    expect($txRollbackCount)->toBe(0)
        ->and($match->refresh()->result->value)->toBe(MatchResult::DRAW);

    Event::assertNotDispatched(MatchResultUpdated::class);

});


test('it can modify match result correctly', function () {

    Event::fake();

    $match = fakeMatchTransactions(MatchResult::CANCEL);


    $txRollbackCount = app(ModifyMatchResultAction::class)(
        match: $match,
        result: MatchResult::MERON,
        note: 'test'
    );

    expect($txRollbackCount)->toBe(11)
        ->and($match->refresh()->result->value)->toBe(MatchResult::MERON);

    Event::assertDispatched(MatchResultUpdated::class);
});
