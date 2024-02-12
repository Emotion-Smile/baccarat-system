<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetTodayResultAction;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;

test('it can get all game result today', function () {

    BaccaratGame::factory(['game_table_id' => 1])->count(10)->create();

    $gameResult = (new BaccaratGameGetTodayResultAction())(gameTableId: 1);

    expect($gameResult->first())->toHaveKeys(['id', 'winner', 'number', 'round'])
        ->and($gameResult->count())->toBe(10);

});
