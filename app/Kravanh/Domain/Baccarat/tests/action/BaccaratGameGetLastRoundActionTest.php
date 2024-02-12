<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetLastRoundAction;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Support\RoundMode;
use App\Kravanh\Domain\Game\tests\GameTestHelper;

test('it can get dragon tiger game last number', function () {

    $table = GameTestHelper::createBaccaratGameWithDefaultTable();

    $lastRound = (new BaccaratGameGetLastRoundAction())(gameTableId: $table->id);

    expect($lastRound)->toBeInt()
        ->and($lastRound)->toBe(1);

    BaccaratGame::factory(['game_table_id' => $table->id])->count(3)->create();

    $lastRound = (new BaccaratGameGetLastRoundAction())(gameTableId: $table->id);
    expect($lastRound)->toBe(1);

    BaccaratGame::factory(['game_table_id' => $table->id, 'round' => 4])->create();

    $lastRound = (new BaccaratGameGetLastRoundAction())(gameTableId: $table->id);
    expect($lastRound)->toBe(4);

    BaccaratGame::factory(['game_table_id' => $table->id, 'round' => 3])->create();

    $lastRound = (new BaccaratGameGetLastRoundAction())(gameTableId: $table->id);
    expect($lastRound)->toBe(3);

    $table1 = GameTestHelper::createGameTable($table->game_id);

    BaccaratGame::factory(['game_table_id' => $table1->id, 'round' => 1])->create();
    $lastRound = (new BaccaratGameGetLastRoundAction())(gameTableId: $table1->id);
    expect($lastRound)->toBe(1);

    $lastRound = (new BaccaratGameGetLastRoundAction())(gameTableId: $table1->id, roundMode: RoundMode::NextRound);
    expect($lastRound)->toBe(2);

});

test('it will skip mode next round if round number is the first round of the day', function () {
    $lastRound = (new BaccaratGameGetLastRoundAction())(gameTableId: 100, roundMode: RoundMode::NextRound);
    expect($lastRound)->toBe(1);
});
