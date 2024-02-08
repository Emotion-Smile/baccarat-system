<?php

use App\Kravanh\Domain\Match\Actions\PayoutCalculatorAction;
use App\Kravanh\Domain\Match\Actions\PayoutDepositorAction;
use App\Kravanh\Domain\Match\Events\AllPayoutDeposited;
use App\Kravanh\Domain\Match\Events\MatchEnded;
use App\Kravanh\Domain\Match\Jobs\BetPayoutJob;
use App\Kravanh\Domain\Match\Models\BetRecord;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Subscribers\DepositPayoutToMember;
use App\Kravanh\Domain\Match\Supports\Enums\BetOn;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Kravanh\Support\Enums\Currency;
use App\Models\User;
use Bavix\Wallet\Models\Transaction;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(['DatabaseSeeder']);
    Matches::factory(['result' => MatchResult::WALA])->createQuietly();
    setupUser(Currency::USD);
});

test(/**
 * @throws Throwable
 */ 'it can payout correctly', function () {

    \Illuminate\Support\Facades\Bus::fake();

    Event::fake();

    $totalFakeUser = 10;

    User::factory(['type' => UserType::MEMBER])
        ->count($totalFakeUser)
        ->has(BetRecord::factory()->count(5), 'bets')
        ->createQuietly();

    //bet not win
    User::factory(['type' => UserType::MEMBER])
        ->count(2)
        ->has(BetRecord::factory(['bet_on' => BetOn::MERON])->count(5), 'bets')
        ->createQuietly();


    MatchEnded::dispatch(Matches::first()->broadCastDataToMember());

    Event::assertDispatched(MatchEnded::class);

    Event::assertListening(
        MatchEnded::class,
        DepositPayoutToMember::class
    );

    (new PayoutCalculatorAction())(Matches::first()->id);

    Bus::assertDispatched(BetPayoutJob::class);

    $match = Matches::first();

    $records = (new PayoutCalculatorAction())->preparePayoutRecords($match, true);

    $records->each(/**
     * @throws Throwable
     */ function ($record) use ($match) {
        (new PayoutDepositorAction())(
            $record->values()->toArray(),
            [
                'environment_id' => $match->environment_id,
                'group_id' => $match->group_id,
                'match_id' => $match->id,
                'fight_number' => $match->fight_number,
                'result' => $match->result->description
            ],
            true
        );
    });

    Event::assertDispatched(AllPayoutDeposited::class);

    $totalPayoutTx = Transaction::query()
        ->where('type', 'deposit')
        ->where('meta->type', 'payout')
        ->count();

    expect($totalPayoutTx)->toBe($totalFakeUser);

});
