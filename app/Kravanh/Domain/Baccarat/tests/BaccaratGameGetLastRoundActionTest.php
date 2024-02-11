<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetLastRoundAction;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;

test('it can get dragon tiger game last number', function () {
    $lastRound = (new BaccaratGameGetLastRoundAction())(1);

    expect($lastRound)->toBeInt()
        ->and($lastRound)->toBe(1);

    BaccaratGame::factory(['game_table_id' => 1])->count(3)->create();

    $lastRound = (new BaccaratGameGetLastRoundAction())(1);
    expect($lastRound)->toBe(1);

    BaccaratGame::factory(['game_table_id' => 1, 'round' => 4])->create();

    $lastRound = (new BaccaratGameGetLastRoundAction())(1);
    expect($lastRound)->toBe(4);

    BaccaratGame::factory(['game_table_id' => 1, 'round' => 3])->create();

    $lastRound = (new BaccaratGameGetLastRoundAction())(1);
    expect($lastRound)->toBe(3);

});
