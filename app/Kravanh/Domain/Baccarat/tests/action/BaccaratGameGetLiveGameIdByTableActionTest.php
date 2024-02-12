<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetLiveGameIdByTableAction;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameNoLiveGameException;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;

test('it can get live game Id by table', function () {
    $game = BaccaratGame::factory()->liveGame()->create();
    $id = (new BaccaratGameGetLiveGameIdByTableAction())(gameTableId: $game->game_table_id);
    expect($id)->toBe($game->id);
});

test('it will throw no live game exception', function () {
    (new BaccaratGameGetLiveGameIdByTableAction())(gameTableId: 0);
})->expectException(BaccaratGameNoLiveGameException::class);
