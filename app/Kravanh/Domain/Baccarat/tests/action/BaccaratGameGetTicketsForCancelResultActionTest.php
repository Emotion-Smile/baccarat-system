<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetTicketsForCancelResultAction;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\Baccarat\tests\BaccaratTestHelper;
use App\Kravanh\Domain\User\Models\Member;

test('verifies retrieval of tickets for canceled game in Dragon Tiger game', function () {

    $game = BaccaratTestHelper::createGameIncludeWinAndLoseTickets();

    $payoutTickets = (new BaccaratGameGetTicketsForCancelResultAction())($game->id);

    expect(BaccaratTicket::count())->toBe(11)
        ->and($payoutTickets->count())->toBe(10) // only one member have two tickets
        ->and($payoutTickets->first()->member)->toBeInstanceOf(Member::class)
        ->and($payoutTickets->first()->toArray())->toHaveKeys(['user_id', 'payout', 'member', 'ids'])
        ->and($payoutTickets->first()->member->toArray())->toHaveKeys(['id', 'currency', 'name']);

});
