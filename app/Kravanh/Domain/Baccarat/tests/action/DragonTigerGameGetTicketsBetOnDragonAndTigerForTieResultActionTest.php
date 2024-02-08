<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetTicketsBetOnDragonAndTigerForTieResultAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGamePayoutAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerGameWinner;
use App\Kravanh\Domain\DragonTiger\tests\DragonTigerTestHelper;
use App\Kravanh\Domain\User\Models\Member;

test('retrieves tickets bet on Dragon and Tiger for tie results in Dragon Tiger game', function () {

    $game = DragonTigerTestHelper::createGameIncludeWinAndLoseTickets();

    $game->winner = DragonTigerGameWinner::Tie;
    $game->saveQuietly();
    $game->refresh();

    $payoutTickets = (new DragonTigerGameGetTicketsBetOnDragonAndTigerForTieResultAction())($game);

    $firstTicket = DragonTigerTicket::find($payoutTickets->first()->ids);
    $secondTicket = DragonTigerTicket::find($payoutTickets->last()->ids);

    expect($payoutTickets->count())->toBe(2)
        ->and($payoutTickets->first()->member)->toBeInstanceOf(Member::class)
        ->and((int)$payoutTickets->first()->payout)->toBe($firstTicket->amount / 2)
        ->and((int)$payoutTickets->last()->payout)->toBe($secondTicket->amount / 2)
        ->and($payoutTickets->first()->toArray())->toHaveKeys(['user_id', 'payout', 'member']);

    $game->winner = DragonTigerGameWinner::Tiger;
    $game->saveQuietly();
    $game->refresh();

    $payoutTickets = (new DragonTigerGameGetTicketsBetOnDragonAndTigerForTieResultAction())($game);
    expect($payoutTickets->isEmpty())->toBeTrue();

});

test('verifies deposit functionality with floating-point amounts in Dragon Tiger game', function () {

    $game = DragonTigerTestHelper::createGameIncludeWinAndLoseTickets();
    $game->winner = DragonTigerGameWinner::Tie;
    $game->saveQuietly();
    $game->refresh();

    $payoutTickets = (new DragonTigerGameGetTicketsBetOnDragonAndTigerForTieResultAction())($game);

    $payoutTickets->map(function ($ticket) {
        $ticket->payout = str_replace('.0000', '.1234', $ticket->payout);
        return $ticket;
    });

    $transactions = (new DragonTigerGamePayoutAction())(
        game: $game,
        tickets: $payoutTickets
    );

    expect($payoutTickets->count())->toBe($transactions->count())
        ->and((int)$transactions->first()->amount)->toBe((int)$payoutTickets->first()->payout);
});
