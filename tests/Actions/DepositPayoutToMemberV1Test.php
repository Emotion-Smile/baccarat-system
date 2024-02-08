<?php

use App\Kravanh\Domain\Match\Events\MatchEnded;
use App\Kravanh\Domain\Match\Models\BetRecord;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Models\PayoutDeposit;
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

test('it can payout v1 correctly', function () {


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


    (new DepositPayoutToMember())->handle(new MatchEnded(Matches::first()->broadCastDataToMember()));


    $totalPayoutTx = Transaction::query()
        ->where('type', 'deposit')
        ->where('meta->type', 'payout')
        ->count();

    expect($totalPayoutTx)->toBe($totalFakeUser)
        ->and(PayoutDeposit::count())->toBe(10);
});
