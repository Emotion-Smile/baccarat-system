<?php

use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\BetOn;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Kravanh\Support\Enums\Currency;
use Bavix\Wallet\Interfaces\Storable;
use Illuminate\Support\Facades\Cache;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\seed;

beforeEach(function () {
    app(Storable::class)->fresh();
    seed(['DatabaseSeeder']);
    Matches::factory()->createQuietly();
});

test('it can bet with KHR currency', function () {

    setupUser(Currency::KHR);

    $member = loginAsMember();
    $totalBalance = $member->getCurrentBalance();
    expect(10_000_000)->toBe($totalBalance);

    $firstBetAmount = 10000;

    postJson(route('member.match.betting'), [
        'betAmount' => $firstBetAmount,
        'betOn' => BetOn::MERON
    ])
        ->assertJson(['type' => 'success'])
        ->assertOk();

    expect($member->getCurrentBalance())
        ->toBe($totalBalance - $firstBetAmount)
        ->and($member->bets()->count())->toBe(1);

    $matchLive = Matches::live($member);
    $matchBetInfoFromCache = Cache::get($matchLive->getCacheKey(Matches::MATCH_BET_INFO));

    $matchLive->meron_total_bet = $matchBetInfoFromCache['meronTotalBet'] ?? 0;
    $matchLive->wala_total_bet = $matchBetInfoFromCache['walaTotalBet'] ?? 0;
    $matchLive->total_ticket = $matchBetInfoFromCache['totalTicket'] ?? 0;

    //expect match
    expect($matchLive->total_ticket)->toBe(1)
        ->and($matchLive->meron_total_bet)->toBe($firstBetAmount)
        ->and($matchLive->wala_total_bet)->toBe(0);

    // expect transaction
    $tx = $member
        ->transactions()
        ->where('type', 'withdraw')
        ->first();

    $txMeta = $tx->meta;

    expect((int)$member->bets()->first()->transaction_id)->toBe($tx->id)
        ->and($txMeta['type'])->toBe('bet')
        ->and($txMeta['fight_number'])->toBe($matchLive->fight_number)
        ->and($txMeta['bet_on'])->toBe(BetOn::MERON)
        ->and((float)$txMeta['payout_rate'])->toBe($matchLive->payoutRate(BetOn::MERON))
        ->and((int)$txMeta['payout'])->toBe((int)($firstBetAmount * (float)$txMeta['payout_rate']))
        ->and($txMeta['amount'])->toBe($firstBetAmount)
        ->and($txMeta['match_id'])->toBe($matchLive->id)
        ->and($txMeta['before_balance'])->toBe($totalBalance)
        ->and($txMeta['current_balance'])->toBe($txMeta['before_balance'] - $firstBetAmount)
        ->and($txMeta['currency'])->toBe(Currency::KHR);


    $totalBalance = $member->getCurrentBalance();
    $secondBetAmount = 20000;

    postJson(route('member.match.betting'), [
        'betAmount' => $secondBetAmount,
        'betOn' => BetOn::WALA
    ])
        ->assertJson(['type' => 'success'])
        ->assertOk();

    expect($member->getCurrentBalance())->toBe($totalBalance - $secondBetAmount)
        ->and($member->bets()->count())->toBe(2);

    $matchLive = Matches::live($member);

    $matchBetInfoFromCache = Cache::get($matchLive->getCacheKey(Matches::MATCH_BET_INFO));

    $matchLive->meron_total_bet = $matchBetInfoFromCache['meronTotalBet'] ?? 0;
    $matchLive->wala_total_bet = $matchBetInfoFromCache['walaTotalBet'] ?? 0;
    $matchLive->total_ticket = $matchBetInfoFromCache['totalTicket'] ?? 0;
    //expect match
    expect($matchLive->total_ticket)->toBe(2)
        ->and($matchLive->meron_total_bet)->toBe($firstBetAmount)
        ->and($matchLive->wala_total_bet)->toBe($secondBetAmount);

    // expect transaction
    $tx = $member
        ->transactions()
        ->where('type', 'withdraw')
        ->orderByDesc('id')
        ->first();

    $txWithdrawMeta = $tx->meta;

    expect((int)$member->bets()->orderByDesc('id')->first()->transaction_id)->toBe($tx->id)
        ->and($txWithdrawMeta['type'])->toBe('bet')
        ->and($txWithdrawMeta['fight_number'])->toBe($matchLive->fight_number)
        ->and($txWithdrawMeta['bet_on'])->toBe(BetOn::WALA)
        ->and((float)$txWithdrawMeta['payout_rate'])->toBe($matchLive->payoutRate(BetOn::WALA))
        ->and((int)$txWithdrawMeta['payout'])->toBe((int)($secondBetAmount * (float)$txWithdrawMeta['payout_rate']))
        ->and($txWithdrawMeta['amount'])->toBe($secondBetAmount)
        ->and($txWithdrawMeta['match_id'])->toBe($matchLive->id)
        ->and($txWithdrawMeta['before_balance'])->toBe($totalBalance)
        ->and($txWithdrawMeta['current_balance'])->toBe($txWithdrawMeta['before_balance'] - $secondBetAmount)
        ->and($txWithdrawMeta['currency'])->toBe(Currency::KHR);

    $member->type = UserType::TRADER;
    $member->saveQuietly();
    $balanceBeforeResult = $member->getCurrentBalance();

    $matchLive->bet_stopped_at = now();
    $matchLive->saveQuietly();

    putJson(route('match.end'), [
        'result' => MatchResult::WALA
    ])
        ->assertJson(['message' => 'Fight# 1 was ended.'])
        ->assertOk();

    $txPayoutMeta = $member
        ->transactions()
        ->where('type', 'deposit')
        ->orderByDesc('id')
        ->first()->meta;

    expect($txPayoutMeta['type'])->toBe('payout')
        ->and($txPayoutMeta['match_id'])->toBe($matchLive->id)
        ->and($txPayoutMeta['before_balance'])->toBe($balanceBeforeResult)
        ->and($txPayoutMeta['current_balance'])->toBe($member->getCurrentBalance())
        ->and($txPayoutMeta['fight_number'])->toBe($matchLive->fight_number)
        ->and($member->getCurrentBalance())->toBe($balanceBeforeResult + (int)($secondBetAmount + ($secondBetAmount * 0.90)));

});

test('it can bet', function ($currency, $deposit, $firstBet, $secondBet) {

    setupUser($currency, $deposit);
    $currency = Currency::fromKey($currency);

    $member = loginAsMember();
    $totalBalance = $member->getCurrentBalance();

    expect(fromKHRtoCurrency($deposit, $currency))->toBe($totalBalance);

    $firstBetAmount = $firstBet;

    postJson(route('member.match.betting'), [
        'betAmount' => $firstBetAmount,
        'betOn' => BetOn::MERON
    ])
        ->assertJson(['type' => 'success'])
        ->assertOk();

    $matchLive = Matches::live($member);

    //assert cache
    expect(Cache::has($member->getCacheKey($member::KEY_LAST_BET)));

    $lastBetAt = $member->getLastBetAt();
    expect($lastBetAt['at'])->not()->toBeNull();
    expect($lastBetAt['fight_number'])->toBe($matchLive->fight_number);

    expect(Cache::get($member->getCacheKey($member::KEY_TODAY_BET_AMOUNT)))
        ->toBe((int)$member->toKHR($firstBetAmount));

    expect(Cache::get($member->getMatchCacheKey($member::KEY_TOTAL_BET_PER_MATCH, $matchLive->id)))
        ->toBe((int)$member->toKHR($firstBetAmount));

    expect($member->getCurrentBalance())->toBe($totalBalance - $firstBetAmount);
    expect($member->bets()->count())->toBe(1);


    $firstBetAmount = (int)toKHR($firstBetAmount, $currency);

    $matchBetInfoFromCache = Cache::get($matchLive->getCacheKey(Matches::MATCH_BET_INFO));

    $matchLive->meron_total_bet = $matchBetInfoFromCache['meronTotalBet'] ?? 0;
    $matchLive->wala_total_bet = $matchBetInfoFromCache['walaTotalBet'] ?? 0;
    $matchLive->total_ticket = $matchBetInfoFromCache['totalTicket'] ?? 0;

    //expect match
    expect($matchLive->total_ticket)->toBe(1);
    expect($matchLive->meron_total_bet)->toBe($firstBetAmount);
    expect($matchLive->wala_total_bet)->toBe(0);

    // expect transaction
    $tx = $member
        ->transactions()
        ->where('type', 'withdraw')
        ->first();

    $txMeta = $tx->meta;

    expect((int)$member->bets()->first()->transaction_id)->toBe($tx->id);
    expect($txMeta['type'])->toBe('bet');
    expect($txMeta['fight_number'])->toBe($matchLive->fight_number);
    expect($txMeta['bet_on'])->toBe(BetOn::MERON);
    expect((float)$txMeta['payout_rate'])->toBe($matchLive->payoutRate(BetOn::MERON));
    expect((int)$txMeta['payout'])->toBe((int)($firstBetAmount * (float)$txMeta['payout_rate']));
    expect($txMeta['amount'])->toBe($firstBetAmount);
    expect($txMeta['match_id'])->toBe($matchLive->id);
    expect($txMeta['before_balance'])->toBe((int)toKHR($totalBalance, $currency));
    expect($txMeta['current_balance'])->toBe($txMeta['before_balance'] - $firstBetAmount);
    expect($txMeta['currency'])->toBe($currency->key);


    // Second bet

    $totalBalance = $member->getCurrentBalance();
    $secondBetAmount = $secondBet;

    postJson(route('member.match.betting'), [
        'betAmount' => $secondBetAmount,
        'betOn' => BetOn::WALA
    ])
        ->assertJson(['type' => 'success'])
        ->assertOk();

    expect($member->getCurrentBalance())->toBe($totalBalance - $secondBetAmount);
    expect($member->bets()->count())->toBe(2);

    $matchLive = Matches::live($member);

    $secondBetAmount = (int)$member->toKHR($secondBetAmount);

    //assert cache
    expect(Cache::has($member->getCacheKey($member::KEY_LAST_BET)));

    $lastBetAt = $member->getLastBetAt();
    expect($lastBetAt['at'])->not()->toBeNull();
    expect($lastBetAt['fight_number'])->toBe($matchLive->fight_number);

    expect(Cache::get($member->getCacheKey($member::KEY_TODAY_BET_AMOUNT)))
        ->toBe($firstBetAmount + $secondBetAmount);

    expect(Cache::get($member->getMatchCacheKey($member::KEY_TOTAL_BET_PER_MATCH, $matchLive->id)))
        ->toBe($firstBetAmount + $secondBetAmount);

    $matchBetInfoFromCache = Cache::get($matchLive->getCacheKey(Matches::MATCH_BET_INFO));

    $matchLive->meron_total_bet = $matchBetInfoFromCache['meronTotalBet'] ?? 0;
    $matchLive->wala_total_bet = $matchBetInfoFromCache['walaTotalBet'] ?? 0;
    $matchLive->total_ticket = $matchBetInfoFromCache['totalTicket'] ?? 0;
    //expect match
    expect($matchLive->total_ticket)->toBe(2);
    expect($matchLive->meron_total_bet)->toBe($firstBetAmount);
    expect($matchLive->wala_total_bet)->toBe($secondBetAmount);

    // expect transaction
    $tx = $member
        ->transactions()
        ->where('type', 'withdraw')
        ->orderByDesc('id')
        ->first();

    $txMeta = $tx->meta;

    expect((int)$member->bets()->orderByDesc('id')->first()->transaction_id)->toBe($tx->id);
    expect($txMeta['type'])->toBe('bet');
    expect($txMeta['fight_number'])->toBe($matchLive->fight_number);
    expect($txMeta['bet_on'])->toBe(BetOn::WALA);
    expect((float)$txMeta['payout_rate'])->toBe($matchLive->payoutRate(BetOn::WALA));
    expect((int)$txMeta['payout'])->toBe((int)($secondBetAmount * (float)$txMeta['payout_rate']));
    expect($txMeta['amount'])->toBe($secondBetAmount);
    expect($txMeta['match_id'])->toBe($matchLive->id);
    expect($txMeta['before_balance'])->toBe((int)toKHR($totalBalance, $currency));
    expect($txMeta['current_balance'])->toBe($txMeta['before_balance'] - $secondBetAmount);
    expect($txMeta['currency'])->toBe($currency->key);
})->with([
    [Currency::KHR, 10_000_000, 10000, 40000],
    [Currency::USD, 10_000_000, 10, 50],
    [Currency::THB, 10_000_000, 30, 100],
    [Currency::VND, 10_000_000, 30000, 50000],
]);


test('it can bet two match live at the time', function ($currency, $deposit, $firstBet, $secondBet) {

    Matches::factory(['group_id' => 2])->createQuietly();

    setupUser($currency, $deposit);
    $currency = Currency::fromKey($currency);

    $member = loginAsMember();
    $totalBalance = $member->getCurrentBalance();

    expect(fromKHRtoCurrency($deposit, $currency))->toBe($totalBalance);

    $firstBetAmount = $firstBet;

    postJson(route('member.match.betting'), [
        'betAmount' => $firstBetAmount,
        'betOn' => BetOn::MERON
    ])
        ->assertJson(['type' => 'success'])
        ->assertOk();

    $matchLive = Matches::live($member);

    //assert cache
    expect(Cache::has($member->getCacheKey($member::KEY_LAST_BET)));

    $lastBetAt = $member->getLastBetAt();
    expect($lastBetAt['at'])->not()->toBeNull();
    expect($lastBetAt['fight_number'])->toBe($matchLive->fight_number);

    expect(Cache::get($member->getCacheKey($member::KEY_TODAY_BET_AMOUNT)))
        ->toBe((int)$member->toKHR($firstBetAmount));

    expect(Cache::get($member->getMatchCacheKey($member::KEY_TOTAL_BET_PER_MATCH, $matchLive->id)))
        ->toBe((int)$member->toKHR($firstBetAmount));

    expect($member->getCurrentBalance())->toBe($totalBalance - $firstBetAmount);
    expect($member->bets()->count())->toBe(1);


    $firstBetAmount = (int)toKHR($firstBetAmount, $currency);

    $matchBetInfoFromCache = Cache::get($matchLive->getCacheKey(Matches::MATCH_BET_INFO));

    $matchLive->meron_total_bet = $matchBetInfoFromCache['meronTotalBet'] ?? 0;
    $matchLive->wala_total_bet = $matchBetInfoFromCache['walaTotalBet'] ?? 0;
    $matchLive->total_ticket = $matchBetInfoFromCache['totalTicket'] ?? 0;

    //expect match
    expect($matchLive->total_ticket)->toBe(1);
    expect($matchLive->meron_total_bet)->toBe($firstBetAmount);
    expect($matchLive->wala_total_bet)->toBe(0);

    // expect transaction
    $tx = $member
        ->transactions()
        ->where('type', 'withdraw')
        ->first();

    $txMeta = $tx->meta;

    expect((int)$member->bets()->first()->transaction_id)->toBe($tx->id);
    expect($txMeta['type'])->toBe('bet');
    expect($txMeta['fight_number'])->toBe($matchLive->fight_number);
    expect($txMeta['bet_on'])->toBe(BetOn::MERON);
    expect((float)$txMeta['payout_rate'])->toBe($matchLive->payoutRate(BetOn::MERON));
    expect((int)$txMeta['payout'])->toBe((int)($firstBetAmount * (float)$txMeta['payout_rate']));
    expect($txMeta['amount'])->toBe($firstBetAmount);
    expect($txMeta['match_id'])->toBe($matchLive->id);
    expect($txMeta['before_balance'])->toBe((int)toKHR($totalBalance, $currency));
    expect($txMeta['current_balance'])->toBe($txMeta['before_balance'] - $firstBetAmount);
    expect($txMeta['currency'])->toBe($currency->key);


    // Second bet

    $totalBalance = $member->getCurrentBalance();
    $secondBetAmount = $secondBet;

    postJson(route('member.match.betting'), [
        'betAmount' => $secondBetAmount,
        'betOn' => BetOn::WALA
    ])
        ->assertJson(['type' => 'success'])
        ->assertOk();

    expect($member->getCurrentBalance())->toBe($totalBalance - $secondBetAmount);
    expect($member->bets()->count())->toBe(2);

    $matchLive = Matches::live($member);

    $secondBetAmount = (int)$member->toKHR($secondBetAmount);

    //assert cache
    expect(Cache::has($member->getCacheKey($member::KEY_LAST_BET)));

    $lastBetAt = $member->getLastBetAt();
    expect($lastBetAt['at'])->not()->toBeNull();
    expect($lastBetAt['fight_number'])->toBe($matchLive->fight_number);

    expect(Cache::get($member->getCacheKey($member::KEY_TODAY_BET_AMOUNT)))
        ->toBe($firstBetAmount + $secondBetAmount);

    expect(Cache::get($member->getMatchCacheKey($member::KEY_TOTAL_BET_PER_MATCH, $matchLive->id)))
        ->toBe($firstBetAmount + $secondBetAmount);


    $matchBetInfoFromCache = Cache::get($matchLive->getCacheKey(Matches::MATCH_BET_INFO));

    $matchLive->meron_total_bet = $matchBetInfoFromCache['meronTotalBet'] ?? 0;
    $matchLive->wala_total_bet = $matchBetInfoFromCache['walaTotalBet'] ?? 0;
    $matchLive->total_ticket = $matchBetInfoFromCache['totalTicket'] ?? 0;

    //expect match
    expect($matchLive->total_ticket)->toBe(2);
    expect($matchLive->meron_total_bet)->toBe($firstBetAmount);
    expect($matchLive->wala_total_bet)->toBe($secondBetAmount);

    // expect transaction
    $tx = $member
        ->transactions()
        ->where('type', 'withdraw')
        ->orderByDesc('id')
        ->first();

    $txMeta = $tx->meta;

    expect((int)$member->bets()->orderByDesc('id')->first()->transaction_id)->toBe($tx->id);
    expect($txMeta['type'])->toBe('bet');
    expect($txMeta['fight_number'])->toBe($matchLive->fight_number);
    expect($txMeta['bet_on'])->toBe(BetOn::WALA);
    expect((float)$txMeta['payout_rate'])->toBe($matchLive->payoutRate(BetOn::WALA));
    expect((int)$txMeta['payout'])->toBe((int)($secondBetAmount * (float)$txMeta['payout_rate']));
    expect($txMeta['amount'])->toBe($secondBetAmount);
    expect($txMeta['match_id'])->toBe($matchLive->id);
    expect($txMeta['before_balance'])->toBe((int)toKHR($totalBalance, $currency));
    expect($txMeta['current_balance'])->toBe($txMeta['before_balance'] - $secondBetAmount);
    expect($txMeta['currency'])->toBe($currency->key);
})->with([
    [Currency::KHR, 10_000_000, 10000, 40000],
    [Currency::USD, 10_000_000, 10, 50],
    [Currency::THB, 10_000_000, 30, 100],
    [Currency::VND, 10_000_000, 30000, 50000],
]);
