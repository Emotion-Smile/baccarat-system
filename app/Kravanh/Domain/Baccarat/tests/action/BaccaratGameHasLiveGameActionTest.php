<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameHasLiveGameAction;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;

test('it can check dragon tiger has live game now', function () {

    $isLive = (new BaccaratGameHasLiveGameAction())(gameTableId: 1);
    expect($isLive)->toBeFalse();

    BaccaratGame::factory(['game_table_id' => 1])->create();

    $isLive = (new BaccaratGameHasLiveGameAction())(gameTableId: 1);
    expect($isLive)->toBeFalse();

    BaccaratGame::factory(['game_table_id' => 1])->liveGame()->create();

    $isLive = (new BaccaratGameHasLiveGameAction())(gameTableId: 1);
    expect($isLive)->toBeTrue();

    $isLive = (new BaccaratGameHasLiveGameAction())(gameTableId: [1, 2]);
    expect($isLive)->toBeTrue();

});
