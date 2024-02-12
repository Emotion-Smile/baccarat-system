<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetTicketsBetOnDragonAndTigerForTieResultAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGamePayoutAction;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\Baccarat\Support\BaccaratGameWinner;
use App\Kravanh\Domain\Baccarat\tests\BaccaratTestHelper;
use App\Kravanh\Domain\User\Models\Member;

test('retrieves tickets bet on Dragon and Tiger for tie results in Dragon Tiger game', function () {

    $game = BaccaratTestHelper::createGameIncludeWinAndLoseTickets();

    $game->winner = BaccaratGameWinner::Tie;
    $game->saveQuietly();
    $game->refresh();

    $payoutTickets = (new BaccaratGameGetTicketsBetOnDragonAndTigerForTieResultAction())($game);

    $firstTicket = BaccaratTicket::find($payoutTickets->first()->ids);
    $secondTicket = BaccaratTicket::find($payoutTickets->last()->ids);

    expect($payoutTickets->count())->toBe(2)
        ->and($payoutTickets->first()->member)->toBeInstanceOf(Member::class)
        ->and((int)$payoutTickets->first()->payout)->toBe($firstTicket->amount / 2)
        ->and((int)$payoutTickets->last()->payout)->toBe($secondTicket->amount / 2)
        ->and($payoutTickets->first()->toArray())->toHaveKeys(['user_id', 'payout', 'member']);

    $game->winner = BaccaratGameWinner::Tiger;
    $game->saveQuietly();
    $game->refresh();

    $payoutTickets = (new BaccaratGameGetTicketsBetOnDragonAndTigerForTieResultAction())($game);
    expect($payoutTickets->isEmpty())->toBeTrue();

});

test('verifies deposit functionality with floating-point amounts in Dragon Tiger game', function () {

    $game = BaccaratTestHelper::createGameIncludeWinAndLoseTickets();
    $game->winner = BaccaratGameWinner::Tie;
    $game->saveQuietly();
    $game->refresh();

    $payoutTickets = (new BaccaratGameGetTicketsBetOnDragonAndTigerForTieResultAction())($game);

    $payoutTickets->map(function ($ticket) {
        $ticket->payout = str_replace('.0000', '.1234', $ticket->payout);
        return $ticket;
    });

    $transactions = (new BaccaratGamePayoutAction())(
        game: $game,
        tickets: $payoutTickets
    );

    expect($payoutTickets->count())->toBe($transactions->count())
        ->and((int)$transactions->first()->amount)->toBe((int)$payoutTickets->first()->payout);
});
