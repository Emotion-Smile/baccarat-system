<?php

use App\Kravanh\Domain\Match\Events\MatchEnded;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use App\Kravanh\Support\Enums\Currency;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(['DatabaseSeeder']);
    setupUser(Currency::USD);
});

test('it can end the match', function () {


    loginAsTrader();

    postJson(route('match.create-new'), [
        'totalPayout' => 190,
        'meronPayout' => 90
    ])
        ->assertOk();


    loginAsTrader();


    Event::fake();

    putJson(route('match.end'), [
        'result' => MatchResult::WALA
    ])
        ->assertJson(['message' => 'Fight# 1 was ended.'])
        ->assertOk();

    $match = Matches::first();

    expect(Cache::has($match->getCacheKey(Matches::MATCH_RESULT_TODAY)))->toBeTrue()
        ->and(Cache::has($match->getCacheKey(Matches::MATCH_BET_INFO)))->toBeFalse()
        ->and($match->isMatchEnded())->toBeTrue();

    $envId = $match->environment_id;
    $groupId = $match->group_id;

    Event::assertDispatched(MatchEnded::class, function ($event)
    use ($envId, $groupId) {
        $payload = [
            'id' => 1,
            'environment_id' => $envId,
            'group_id' => $groupId,
            'fight_number' => 1,
            'meron_payout' => '#.##',
            'meron_total_bet' => priceFormat(0, ''),
            'wala_payout' => '#.##',
            'wala_total_bet' => priceFormat(0, ''),
            'status' => 'close',
            'bet_opened' => true,
            'bet_status' => 'close',
            'bet_closed' => true,
            'match_end' => true,
            'result' => MatchResult::fromValue(MatchResult::WALA)->description,
            'disable_bet_button' => true,
            'total_ticket' => 0,
            'payout_adjusted' => false
        ];
        return count(array_diff_assoc($payload, $event->match)) === 0;
    });


    putJson(route('match.end'), [
        'result' => MatchResult::WALA
    ])
        ->assertJson(['type' => 'error'])
        ->assertOk();

//    assertDatabaseHas('matches', [
//        'total_ticket' => $payload['totalTicket'],
//        'meron_total_bet' => $payload['meronTotalBet'],
//        'meron_total_payout' => $payload['meronTotalPayout'],
//        'wala_total_bet' => $payload['walaTotalBet'],
//        'wala_total_payout' => $payload['walaTotalPayout']
//    ]);


});
