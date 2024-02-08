<?php

use App\Kravanh\Domain\Match\Events\MatchEnded;
use App\Kravanh\Domain\Match\Events\MatchEndedResultSummary;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\User\Models\Trader;
use Symfony\Component\HttpFoundation\Response;
use function Pest\Laravel\actingAs;

test('it can not submit result if match already ended', function () {
    Event::fake();
    $trader = Trader::factory()->createQuietly();

    Matches::factory([
        'group_id' => $trader->group_id,
        'match_end_at' => now(),
    ])->createQuietly();

    actingAs($trader)
        ->postJson(route('api.match.submit-result'), [
            'result' => 1,
        ])
        ->assertStatus(Response::HTTP_BAD_REQUEST)
        ->assertJson([
            'message' => 'Oops, we do not have a live match for submitting the result.'
        ]);

    Event::assertNotDispatched(MatchEndedResultSummary::class);
    Event::assertNotDispatched(MatchEnded::class);
});

test('it can submit result', function () {

    Event::fake();
    $trader = Trader::factory()->createQuietly();

    $match = Matches::factory([
        'group_id' => $trader->group_id,
    ])->createQuietly();

    actingAs($trader)
        ->postJson(route('api.match.submit-result'), [
            'result' => 1,
        ])
        ->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'fightNumber' => $match->fight_number
        ]);

    Event::assertDispatched(MatchEndedResultSummary::class);
    Event::assertDispatched(MatchEnded::class);

    $match->refresh();

    expect($match->isMatchEnded())->toBeTrue()
        ->and($match->result->value)->toBe(1)
        ->and($match->isBettingClosed())->toBeTrue()
        ->and($match->isBettingOpened())->toBeTrue();


});
