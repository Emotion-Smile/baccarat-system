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
        ->putJson(route('dragon-tiger.resubmit-result'))
        ->assertJsonValidationErrors(['dragonResult', 'dragonType', 'tigerResult', 'tigerType', 'gameId']);
});

test('successful game result resubmission in dragon tiger game', function () {

    seed(GameSeeder::class);

    $trader = Trader::factory()->dragonTigerTrader()->create();

    $game = BaccaratGame::factory([
        'game_table_id' => $trader->getGameTableId(),
        'round' => 1,
        'number' => 1,
    ])
        ->create();

    Carbon::setTestNow(now()->addMinute());
    actingAs($trader)
        ->putJson(route('dragon-tiger.resubmit-result'), [
            'gameId' => $game->id,
            'dragonResult' => 13,
            'dragonType' => BaccaratCard::Diamond,
            'tigerResult' => 12,
            'tigerType' => BaccaratCard::Heart,
        ])
        ->assertJson([
            'type' => 'ok',
            'message' => 'The result was resubmitted successfully.',
        ]);

});
