<?php

use App\Kravanh\Domain\Match\Events\MatchBenefited;
use App\Kravanh\Domain\Match\Events\MatchCreated;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\User\Models\Trader;
use Symfony\Component\HttpFoundation\Response;
use function Pest\Laravel\actingAs;

test('it cannot create new match if has live match', function () {

    Event::fake();
    $trader = Trader::factory()->createQuietly();

    Matches::factory([
        'group_id' => $trader->group_id
    ])->createQuietly();

    actingAs($trader)
        ->postJson(route('api.match.new'), [
            'totalPayout' => 200,
            'meronPayout' => 100
        ])
        ->assertStatus(Response::HTTP_BAD_REQUEST)
        ->assertJson(['message' => 'Please end the current match before creating a new one.']);

    Event::assertNotDispatched(MatchCreated::class);
    Event::assertNotDispatched(MatchBenefited::class);

});

test('it can create new match', function () {
    Event::fake();
    $trader = Trader::factory()->createQuietly();

    actingAs($trader)
        ->postJson(route('api.match.new'), [
            'totalPayout' => 200,
            'meronPayout' => 100
        ])
        ->assertStatus(Response::HTTP_OK)
        ->assertJson(['fightNumber' => Matches::first()->fight_number]);

    Event::assertDispatched(MatchCreated::class);
    Event::assertDispatched(MatchBenefited::class);
});

