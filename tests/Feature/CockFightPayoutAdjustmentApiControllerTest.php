<?php

use App\Kravanh\Domain\Match\Events\MatchPayoutUpdated;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\User\Models\Trader;
use Symfony\Component\HttpFoundation\Response;
use function Pest\Laravel\actingAs;

test('it can not adjust payout if match not exist', function () {
    Event::fake();
    $trader = Trader::factory()->createQuietly();
    actingAs($trader)
        ->postJson(route('api.match.adjust'), [
            'totalPayout' => 200,
            'meronPayout' => 100
        ])
        ->assertStatus(Response::HTTP_BAD_REQUEST)
        ->assertJson([
            'message' => 'Oops, you cannot adjust the payout because the match does not exist.'
        ]);

    Event::assertNotDispatched(MatchPayoutUpdated::class);
});

test('Max payout can not greater than 150', function () {

    $trader = Trader::factory()->createQuietly();
    Matches::factory([
        'group_id' => $trader->group_id
    ])->createQuietly();

    actingAs($trader)
        ->postJson(route('api.match.adjust'), [
            'totalPayout' => 210,
            'meronPayout' => 151
        ])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

test('it can adjust payout', function () {

    Event::fake();

    $trader = Trader::factory()->createQuietly();

    $match = Matches::factory([
        'group_id' => $trader->group_id,
    ])->createQuietly();

    actingAs($trader)
        ->postJson(route('api.match.adjust'), [
            'totalPayout' => 200,
            'meronPayout' => 100
        ])
        ->ray()
        ->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'meronTotalBetAmount' => 0,
            'walaTotalBetAmount' => 0,
            'totalTicket' => 0,
            'meronPayoutRate' => '1.0',
            'walaPayoutRate' => '1.0',
            'fightNumber' => $match->fight_number
        ]);

    $match->refresh();

    expect($match->payout_total)->toBe(200)
        ->and($match->payout_meron)->toBe(100);

    Event::assertDispatched(MatchPayoutUpdated::class);
});
