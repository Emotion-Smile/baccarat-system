<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetLiveGameByTableAction;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameNoLiveGameException;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;

test('it can get live game by table', function () {

    $game = DragonTigerGame::factory()->liveGame()->create();
    $liveGame = (new DragonTigerGameGetLiveGameByTableAction())(gameTableId: $game->game_table_id);
    expect($liveGame)->toBeInstanceOf(DragonTigerGame::class)
        ->and($liveGame->game_table_id)->toBe($game->game_table_id);
});

test('it will throw no live game exception', function () {
    (new DragonTigerGameGetLiveGameByTableAction())(gameTableId: 1);
})->expectException(DragonTigerGameNoLiveGameException::class);
