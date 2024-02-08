<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameCreateManagerAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameCreateData;
use App\Kravanh\Domain\DragonTiger\Events\DragonTigerGameCreated;
use App\Kravanh\Domain\User\Models\Trader;
use Illuminate\Support\Facades\Event;

test('it can create new dragon tiger game', function () {
    Event::fake();

    $trader = Trader::factory()->dragonTigerTrader()->create();
    $data = DragonTigerGameCreateData::from($trader);

    $game = (new DragonTigerGameCreateManagerAction())(data: $data);

    expect($game->refresh()->isLive())->toBeTrue();
    Event::assertDispatched(DragonTigerGameCreated::class);

});
