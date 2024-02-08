<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetLastRoundAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Support\RoundMode;
use App\Kravanh\Domain\Game\tests\GameTestHelper;

test('it can get dragon tiger game last number', function () {

    $table = GameTestHelper::createDragonTigerGameWithDefaultTable();

    $lastRound = (new DragonTigerGameGetLastRoundAction())(gameTableId: $table->id);

    expect($lastRound)->toBeInt()
        ->and($lastRound)->toBe(1);

    DragonTigerGame::factory(['game_table_id' => $table->id])->count(3)->create();

    $lastRound = (new DragonTigerGameGetLastRoundAction())(gameTableId: $table->id);
    expect($lastRound)->toBe(1);

    DragonTigerGame::factory(['game_table_id' => $table->id, 'round' => 4])->create();

    $lastRound = (new DragonTigerGameGetLastRoundAction())(gameTableId: $table->id);
    expect($lastRound)->toBe(4);

    DragonTigerGame::factory(['game_table_id' => $table->id, 'round' => 3])->create();

    $lastRound = (new DragonTigerGameGetLastRoundAction())(gameTableId: $table->id);
    expect($lastRound)->toBe(3);

    $table1 = GameTestHelper::createGameTable($table->game_id);

    DragonTigerGame::factory(['game_table_id' => $table1->id, 'round' => 1])->create();
    $lastRound = (new DragonTigerGameGetLastRoundAction())(gameTableId: $table1->id);
    expect($lastRound)->toBe(1);

    $lastRound = (new DragonTigerGameGetLastRoundAction())(gameTableId: $table1->id, roundMode: RoundMode::NextRound);
    expect($lastRound)->toBe(2);

});

test('it will skip mode next round if round number is the first round of the day', function () {
    $lastRound = (new DragonTigerGameGetLastRoundAction())(gameTableId: 100, roundMode: RoundMode::NextRound);
    expect($lastRound)->toBe(1);
});
