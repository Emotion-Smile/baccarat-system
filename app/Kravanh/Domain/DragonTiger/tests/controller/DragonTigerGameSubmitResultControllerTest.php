<?php

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerCard;
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

    DragonTigerGame::factory([
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
            'dragonType' => DragonTigerCard::Diamond,
            'tigerResult' => 12,
            'tigerType' => DragonTigerCard::Heart,
        ])
        ->assertJson([
            'type' => 'ok',
            'message' => 'The result was submitted successfully.',
        ]);

});
