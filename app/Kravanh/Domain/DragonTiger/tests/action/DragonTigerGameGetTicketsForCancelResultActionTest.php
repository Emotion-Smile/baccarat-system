<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetTicketsForCancelResultAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use App\Kravanh\Domain\DragonTiger\tests\DragonTigerTestHelper;
use App\Kravanh\Domain\User\Models\Member;

test('verifies retrieval of tickets for canceled game in Dragon Tiger game', function () {

    $game = DragonTigerTestHelper::createGameIncludeWinAndLoseTickets();

    $payoutTickets = (new DragonTigerGameGetTicketsForCancelResultAction())($game->id);

    expect(DragonTigerTicket::count())->toBe(11)
        ->and($payoutTickets->count())->toBe(10) // only one member have two tickets
        ->and($payoutTickets->first()->member)->toBeInstanceOf(Member::class)
        ->and($payoutTickets->first()->toArray())->toHaveKeys(['user_id', 'payout', 'member', 'ids'])
        ->and($payoutTickets->first()->member->toArray())->toHaveKeys(['id', 'currency', 'name']);

});
