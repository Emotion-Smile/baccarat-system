<?php

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\User\Models\Trader;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

test('successful game result submission in dragon tiger game', function () {

    seed(GameSeeder::class);

    $trader = Trader::factory()->dragonTigerTrader()->create();

    DragonTigerGame::factory([
        'game_table_id' => $trader->getGameTableId(),
        'round' => 1,
        'number' => 1,
    ])
        ->liveGame()
        ->create();

    actingAs($trader)
        ->putJson(route('dragon-tiger.submit-cancel-result'))
        ->assertJson([
            'type' => 'ok',
            'message' => 'The result was submitted successfully.',
        ]);

});
