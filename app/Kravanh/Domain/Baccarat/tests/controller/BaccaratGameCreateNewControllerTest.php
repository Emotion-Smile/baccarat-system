<?php

use App\Kravanh\Domain\Baccarat\App\Trader\Controllers\Api\BaccaratGameCreateNewController;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Support\RoundMode;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\User\Models\Trader;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

test('verifies successful creation of a new Dragon Tiger game', function () {

    seed(GameSeeder::class);

    $trader = Trader::factory()->dragonTigerTrader()->create();

    actingAs($trader)
        ->postJson(action(BaccaratGameCreateNewController::class))
        ->assertJson([
            'type' => 'ok',
            'message' => 'New game created: #1_1',
        ]);

});

test('verifies successful creation of a new Dragon Tiger game with specify betting interval', function () {

    seed(GameSeeder::class);

    $trader = Trader::factory()->dragonTigerTrader()->create();

    actingAs($trader)
        ->postJson(action(BaccaratGameCreateNewController::class), [
            'betIntervalInSecond' => 99,
        ])
        ->assertJson([
            'type' => 'ok',
            'message' => 'New game created: #1_1',
        ]);

    expect(BaccaratGame::first()->bettingInterval())->toBeGreaterThan(60);

});

test('creation of next round in dragon tiger game', function () {

    seed(GameSeeder::class);

    $trader = Trader::factory()->dragonTigerTrader()->create();

    //Game Number: #1/1
    BaccaratGame::factory([
        'game_table_id' => $trader->getGameTableId(),
        'round' => 1,
        'number' => 1,
    ])->create();

    //Game Number: #2/1
    actingAs($trader)
        ->postJson(action(BaccaratGameCreateNewController::class), [
            'roundMode' => RoundMode::NextRound,
        ])
        ->assertJson([
            'type' => 'ok',
            'message' => 'New game created: #2_1',
        ]);

});

test('validates response for failed Dragon Tiger game creation due to existing live game', function () {

    seed(GameSeeder::class);

    $trader = Trader::factory()->dragonTigerTrader()->create();

    BaccaratGame::factory(['game_table_id' => $trader->getGameTableId()])->liveGame()->create();

    actingAs($trader)
        ->postJson(route('dragon-tiger.create-new-game'))
        ->assertJson([
            'type' => 'failed',
            'message' => 'Dragon Tiger game has live game',
        ]);

});
