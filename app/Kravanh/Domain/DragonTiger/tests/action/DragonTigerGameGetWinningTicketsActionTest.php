<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetWinningTicketsAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use App\Kravanh\Domain\DragonTiger\tests\DragonTigerTestHelper;
use App\Kravanh\Domain\User\Models\Agent;
use App\Kravanh\Domain\User\Models\Member;
use function Pest\Laravel\assertDatabaseCount;

test('verify correct identification and properties of winning tickets in dragon tiger game', function () {


    $game = DragonTigerTestHelper::createGameIncludeWinAndLoseTickets();
    $winningTickets = (new DragonTigerGameGetWinningTicketsAction())($game);
    assertDatabaseCount(DragonTigerTicket::class, 11);


    expect($game->tickets->count())->toBe(11)
        ->and($winningTickets->count())->toBe(4)
        ->and($winningTickets->last()->ids)->toContain(',') // 4,5
        ->and((int)$winningTickets->last()->payout)->toBe(160_000)
        ->and($winningTickets->first()->member)->not()->toBeInstanceOf(Agent::class)
        ->and($winningTickets->first()->member)->toBeInstanceOf(Member::class)
        ->and($winningTickets->first()->toArray())->toHaveKeys(['user_id', 'payout', 'member']);

});

test('ensures correct query of winning tickets in Dragon Tiger game', function () {

    $game = DragonTigerTestHelper::createGameIncludeWinAndLoseTickets();

    $winningTickets = DragonTigerTicket::query()
        ->onlyWinningTickets(game: $game)
        ->get();

    $winningTicketsFromCollection = $winningTickets->filter(fn(DragonTigerTicket $ticket) => $ticket->isWinning());

    assertDatabaseCount(DragonTigerTicket::class, 11);

    expect($game->tickets->count())->toBe(11)
        ->and($winningTickets->count())->toBe(5)
        ->and($winningTickets->toArray())->toMatchArray($winningTicketsFromCollection->toArray());
});
