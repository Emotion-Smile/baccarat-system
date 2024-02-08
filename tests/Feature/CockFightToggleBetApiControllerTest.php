<?php


use App\Kravanh\Domain\Match\Events\MatchBetClosed;
use App\Kravanh\Domain\Match\Events\MatchBetOpened;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\User\Models\Trader;
use Symfony\Component\HttpFoundation\Response;
use function Pest\Laravel\actingAs;

test('it can not request open or close bet if match not exist', function () {

    Event::fake();
    $trader = Trader::factory()->createQuietly();
    $expectMessage = ['message' => 'Oops, you cannot open or close betting because the match does not exist.'];

    actingAs($trader)
        ->postJson(route('api.match.toggle-bet'), [
            'betStatus' => true
        ])
        ->assertStatus(Response::HTTP_BAD_REQUEST)
        ->assertJson($expectMessage);

    Event::assertNotDispatched(MatchBetOpened::class);

    actingAs($trader)
        ->postJson(route('api.match.toggle-bet'), [
            'betStatus' => false
        ])
        ->assertStatus(Response::HTTP_BAD_REQUEST)
        ->assertJson($expectMessage);


    Event::assertNotDispatched(MatchBetClosed::class);

});

test('it can not request re-open bet', function () {
    Event::fake();

    $trader = Trader::factory()->createQuietly();
    Matches::factory([
        'group_id' => $trader->group_id,
    ])->createQuietly();

    actingAs($trader)
        ->postJson(route('api.match.toggle-bet'), [
            'betStatus' => true
        ])
        ->assertStatus(Response::HTTP_BAD_REQUEST)
        ->assertJson([
            'message' => 'Oops, we did not allow to re-open betting for the single match.'
        ]);

    Event::assertNotDispatched(MatchBetOpened::class);
});

test('it can request open bet', function () {
    Event::fake();

    $trader = Trader::factory()->createQuietly();

    $match = Matches::factory([
        'group_id' => $trader->group_id,
        'bet_started_at' => null,
        'bet_stopped_at' => null
    ])->createQuietly();

    actingAs($trader)
        ->postJson(route('api.match.toggle-bet'), [
            'betStatus' => true
        ])
        ->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'fightNumber' => $match->fight_number,
            'betStatus' => 'open'
        ]);

    $match->refresh();

    expect($match->isBettingOpened())->toBeTrue()
        ->and($match->isBettingClosed())->toBeFalse();

    Event::assertDispatched(MatchBetOpened::class);
});


test('it can request close bet', function () {
    //when request close bet it will set start_bet_at = now() if it null
    Event::fake();

    $trader = Trader::factory()->createQuietly();

    $match = Matches::factory([
        'group_id' => $trader->group_id,
        'bet_started_at' => null,
        'bet_stopped_at' => null
    ])->createQuietly();

    actingAs($trader)
        ->postJson(route('api.match.toggle-bet'), [
            'betStatus' => false
        ])
        ->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'fightNumber' => $match->fight_number,
            'betStatus' => 'close'
        ]);

    $match->refresh();

    expect($match->isBettingOpened())->toBeTrue()
        ->and($match->isBettingClosed())->toBeTrue();

    Event::assertDispatched(MatchBetClosed::class);
});

