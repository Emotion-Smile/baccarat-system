<?php

use App\Kravanh\Domain\Match\Events\MatchBenefited;
use App\Kravanh\Domain\Match\Events\MatchCreated;
use App\Kravanh\Domain\Match\Events\MatchPayoutUpdated;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Support\Enums\Currency;
use Illuminate\Support\Facades\Date;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\postJson;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(['DatabaseSeeder']);
    setupUser(Currency::USD);
});

test('it can create new match and adjust payout', function ($trader) {
    $trader1 = loginAsTrader($trader);

    // GROUP1
    postJson(route('match.create-new'), [
        'totalPayout' => 190,
        'meronPayout' => 90
    ])
        ->assertOk();

    $match1 = Matches::where('group_id', $trader1->group_id)->first();
    expect($match1->user_id)->toBe($trader1->id)
        //->and($match1->environment_id)->toBe($matchCache->envId)
        //->and($match1->group_id)->toBe($trader1->group_id)
        ->and($match1->fight_number)->toBe(1)
        ->and($match1->match_date->format('Y-m-d'))
        ->toBe(Date::today()->format('Y-m-d'))
        ->and($match1->payout_meron)->toBe(90)
        ->and($match1->payout_wala)->toBe(100)
        ->and($match1->bet_opened)->toBeFalse()
        ->and($match1->bet_closed)->toBeFalse()
        ->and($match1->match_end)->toBeFalse()
        ->and(Cache::has($match1->getCacheKey(Matches::MATCH_NO_LIVE)))->toBeFalse()
        ->and(Cache::get($match1->getCacheKey(Matches::MATCH_LIVE)))->toBeInstanceOf(Matches::class)
        ->and($match1->getLastFightNumber())->toBe(1);

    Event::fake();
    MatchCreated::dispatch($match1->broadCastDataToMember());

    $envId = $trader1->environment_id;
    $groupId = $trader1->group_id;

    Event::assertDispatched(MatchCreated::class, function ($event)
    use ($envId, $groupId, $match1) {
        $payload = [
            'id' => $match1->id,
            'environment_id' => $envId,
            'group_id' => $groupId,
            'fight_number' => $match1->fight_number,
            'meron_payout' => '#.##',
            'meron_total_bet' => priceFormat(0, ''),
            'wala_payout' => '#.##',
            'wala_total_bet' => priceFormat(0, ''),
            'status' => 'close',
            'bet_opened' => false,
            'bet_status' => 'close',
            'bet_closed' => false,
            'match_end' => false,
            'result' => 'None',
            'disable_bet_button' => true,
            'total_ticket' => 0,
            'payout_adjusted' => false
        ];

        return count(array_diff_assoc($payload, $event->match)) === 0;
    });

    MatchBenefited::dispatch(Matches::estimateBenefit($envId, $groupId, true));
    Event::assertDispatched(MatchBenefited::class, function ($event)
    use ($envId, $groupId) {
        $payload = [
            'id' => 0,
            'environment_id' => $envId,
            'group_id' => $groupId,
            'wala_benefit' => 0,
            'meron_benefit' => 0,
            'total_ticket' => 0
        ];

        return count(array_diff_assoc($payload, $event->match)) === 0;
    });

    postJson(route('match.create-new'), [
        'totalPayout' => 180,
        'meronPayout' => 100
    ])
        ->assertJson([
            'message' => 'Fight# 1 payout adjusted'
        ])
        ->assertOk();

    assertDatabaseCount('matches', 1);
    $match1->refresh();
    expect($match1->payout_wala)->toBe(80);

    MatchPayoutUpdated::dispatch($match1->broadCastDataToMember());
    Event::assertDispatched(MatchPayoutUpdated::class, function ($event)

    use ($envId, $groupId, $match1) {
        $payload = [
            'id' => $match1->id,
            'environment_id' => $envId,
            'group_id' => $groupId,
            'fight_number' => $match1->fight_number,
            'meron_payout' => '1.00',
            'meron_total_bet' => priceFormat(0, ''),
            'wala_payout' => '0.80',
            'wala_total_bet' => priceFormat(0, ''),
            'status' => 'close',
            'bet_opened' => false,
            'bet_status' => 'close',
            'bet_closed' => false,
            'match_end' => false,
            'result' => 'None',
            'disable_bet_button' => true,
            'total_ticket' => 0,
            'payout_adjusted' => true
        ];
        return count(array_diff_assoc($payload, $event->match)) === 0;
    });


})->with([
    ['trader'], //env:1 group: 1
    ['trader_1'] //env:1 group: 2
]);

test('it can live two new match at the same time', function () {

    $trader1 = loginAsTrader();

    // GROUP 1
    postJson(route('match.create-new'), [
        'totalPayout' => 190,
        'meronPayout' => 90
    ])
        ->assertOk();

    assertDatabaseCount('matches', 1);
    $match1 = Matches::live($trader1);

    expect($match1->fight_number)->toBe(1)
        ->and($match1->group_id)->toBe($trader1->group_id);

    // Group 2
    $oldGroupId = $trader1->group_id;
    $nextGroupId = $oldGroupId + 1;
    $trader1->group_id = $nextGroupId;
    $trader1->saveQuietly();

    postJson(route('match.create-new'), [
        'totalPayout' => 190,
        'meronPayout' => 90
    ])->assertOk();

    $match2 = Matches::live($trader1);

    assertDatabaseCount('matches', 2);
    expect($match2->fight_number)->toBe(1)
        ->and($match2->group_id)->toBe($nextGroupId);

    $match2->endMatch(1);
    expect(Matches::live($trader1))->toBeNull();


    postJson(route('match.create-new'), [
        'totalPayout' => 190,
        'meronPayout' => 90
    ])->assertOk();

    $match2 = Matches::live($trader1);

    expect($match2->fight_number)->toBe(2)
        ->and($match2->group_id)->toBe($nextGroupId);

    $trader1->group_id = $oldGroupId;
    $trader1->saveQuietly();
    $match1 = Matches::live($trader1);
    expect($match1->fight_number)->toBe(1);
    $match1->endMatch(1);

    expect(Matches::live($trader1))->toBeNull();

    postJson(route('match.create-new'), [
        'totalPayout' => 190,
        'meronPayout' => 90
    ])->assertOk();

    $match1 = Matches::live($trader1);
    expect($match1->fight_number)->toBe(2)
        ->and($match1->group_id)->toBe($oldGroupId);

    assertDatabaseCount('matches', 4);
});
