<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetLiveGameByTableAction;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameNoLiveGameException;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;

test('it can get live game by table', function () {

    $game = BaccaratGame::factory()->liveGame()->create();
    $liveGame = (new BaccaratGameGetLiveGameByTableAction())(gameTableId: $game->game_table_id);
    expect($liveGame)->toBeInstanceOf(BaccaratGame::class)
        ->and($liveGame->game_table_id)->toBe($game->game_table_id);
});

test('it will throw no live game exception', function () {
    (new BaccaratGameGetLiveGameByTableAction())(gameTableId: 1);
})->expectException(BaccaratGameNoLiveGameException::class);
