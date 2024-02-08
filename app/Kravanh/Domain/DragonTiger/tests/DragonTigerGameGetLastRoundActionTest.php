<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetLastRoundAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;

test('it can get dragon tiger game last number', function () {
    $lastRound = (new DragonTigerGameGetLastRoundAction())(1);

    expect($lastRound)->toBeInt()
        ->and($lastRound)->toBe(1);

    DragonTigerGame::factory(['game_table_id' => 1])->count(3)->create();

    $lastRound = (new DragonTigerGameGetLastRoundAction())(1);
    expect($lastRound)->toBe(1);

    DragonTigerGame::factory(['game_table_id' => 1, 'round' => 4])->create();

    $lastRound = (new DragonTigerGameGetLastRoundAction())(1);
    expect($lastRound)->toBe(4);

    DragonTigerGame::factory(['game_table_id' => 1, 'round' => 3])->create();

    $lastRound = (new DragonTigerGameGetLastRoundAction())(1);
    expect($lastRound)->toBe(3);

});
