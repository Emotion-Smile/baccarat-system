<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameCreateManagerAction;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameCreateData;
use App\Kravanh\Domain\Baccarat\Events\BaccaratGameCreated;
use App\Kravanh\Domain\User\Models\Trader;
use Illuminate\Support\Facades\Event;

test('it can create new dragon tiger game', function () {
    Event::fake();

    $trader = Trader::factory()->dragonTigerTrader()->create();
    $data = BaccaratGameCreateData::from($trader);

    $game = (new BaccaratGameCreateManagerAction())(data: $data);

    expect($game->refresh()->isLive())->toBeTrue();
    Event::assertDispatched(BaccaratGameCreated::class);

});
