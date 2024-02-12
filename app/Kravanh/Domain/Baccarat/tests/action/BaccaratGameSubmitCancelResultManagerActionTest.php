<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameSubmitCancelResultManagerAction;
use App\Kravanh\Domain\Baccarat\Events\BaccaratGameResultSubmitted;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Support\Facades\Event;

test('it successfully submits cancellation result for dragon tiger game', function () {

    Event::fake();

    $game = BaccaratGame::factory(['user_id' => 1])->create();
    BaccaratTicket::factory([
        'dragon_tiger_game_id' => $game->id,
        'amount' => 4000, 'payout' => 4000,
        'payout_rate' => 1]
    )->count(5)->create();

    $isOK = (new BaccaratGameSubmitCancelResultManagerAction())(
        dragonTigerGameId: $game->id,
        userId: 1
    );

    Event::assertDispatched(BaccaratGameResultSubmitted::class);

    expect($isOK)->toBeTrue()
        ->and(Transaction::count())->toBe(5);

    Transaction::all()->each(function ($transaction) {
        expect((int) $transaction->amount)->toBe(4000);
    });
});
