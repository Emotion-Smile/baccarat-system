<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameRoundNumberIsDuplicatedAction;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;

test('it can check dragon tiger game duplicate round number', function () {

    $isDuplicated = (new BaccaratGameRoundNumberIsDuplicatedAction())(gameTableId: 1, round: 1);
    expect($isDuplicated)->toBeFalse();

    $dragonTigerGame = BaccaratGame::factory(['game_table_id' => 1, 'round' => 4])->create();
    $isDuplicated = (new BaccaratGameRoundNumberIsDuplicatedAction())(gameTableId: 1, round: 4);
    $dragonTigerGame->delete();
    expect($isDuplicated)->toBeTrue();


    BaccaratGame::factory(['game_table_id' => 1, 'round' => 4, 'created_at' => now()->subDay()])->create();
    $isDuplicated = (new BaccaratGameRoundNumberIsDuplicatedAction())(gameTableId: 1, round: 4);

    expect($isDuplicated)->toBeFalse();

});
