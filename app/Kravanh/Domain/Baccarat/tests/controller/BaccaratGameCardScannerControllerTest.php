<?php

use App\Kravanh\Domain\Baccarat\Events\BaccaratCardScanned;
use App\Kravanh\Domain\User\Models\Trader;
use Illuminate\Support\Facades\Event;

use function Pest\Laravel\actingAs;

test('it can handle card scanner request', function () {
    Event::fake();

    $dealer = Trader::factory(['name' => 'dragon_tiger_dealer'])->dragonTigerTrader()->create();

    actingAs($dealer)->post(route('dragon-tiger.card-scanner'), [
        'code' => 1010,
        'card' => 'tiger',
    ])
        ->assertOk()
        ->assertJson(['cardName' => 'ace_of_spades']);

    Event::assertDispatched(BaccaratCardScanned::class);
});
