<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameSubmitCancelResultManagerAction;
use App\Kravanh\Domain\DragonTiger\Events\DragonTigerGameResultSubmitted;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Support\Facades\Event;

test('it successfully submits cancellation result for dragon tiger game', function () {

    Event::fake();

    $game = DragonTigerGame::factory(['user_id' => 1])->create();
    DragonTigerTicket::factory([
        'dragon_tiger_game_id' => $game->id,
        'amount' => 4000, 'payout' => 4000,
        'payout_rate' => 1]
    )->count(5)->create();

    $isOK = (new DragonTigerGameSubmitCancelResultManagerAction())(
        dragonTigerGameId: $game->id,
        userId: 1
    );

    Event::assertDispatched(DragonTigerGameResultSubmitted::class);

    expect($isOK)->toBeTrue()
        ->and(Transaction::count())->toBe(5);

    Transaction::all()->each(function ($transaction) {
        expect((int) $transaction->amount)->toBe(4000);
    });
});
