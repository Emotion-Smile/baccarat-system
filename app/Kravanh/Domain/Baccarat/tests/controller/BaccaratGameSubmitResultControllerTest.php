<?php

use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\User\Models\Trader;
use Carbon\Carbon;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

test('validation of required fields for dragon tiger game result submission', function () {

    seed(GameSeeder::class);

    $trader = Trader::factory()->dragonTigerTrader()->create();

    Carbon::setTestNow(now()->addMinute());
    actingAs($trader)
        ->putJson(route('dragon-tiger.submit-result'))
        ->assertJsonValidationErrors(['dragonResult', 'dragonType', 'tigerResult', 'tigerType']);
});

test('successful game result submission in dragon tiger game', function () {

    seed(GameSeeder::class);

    $trader = Trader::factory()->dragonTigerTrader()->create();

    BaccaratGame::factory([
        'game_table_id' => $trader->getGameTableId(),
        'round' => 1,
        'number' => 1,
    ])
        ->liveGame()
        ->create();

    Carbon::setTestNow(now()->addMinute());
    actingAs($trader)
        ->putJson(route('dragon-tiger.submit-result'), [
            'dragonResult' => 13,
            'dragonType' => BaccaratCard::Diamond,
            'tigerResult' => 12,
            'tigerType' => BaccaratCard::Heart,
        ])
        ->assertJson([
            'type' => 'ok',
            'message' => 'The result was submitted successfully.',
        ]);

});
