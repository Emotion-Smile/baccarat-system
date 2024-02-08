<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetLiveGameIdByTableAction;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameNoLiveGameException;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;

test('it can get live game Id by table', function () {
    $game = DragonTigerGame::factory()->liveGame()->create();
    $id = (new DragonTigerGameGetLiveGameIdByTableAction())(gameTableId: $game->game_table_id);
    expect($id)->toBe($game->id);
});

test('it will throw no live game exception', function () {
    (new DragonTigerGameGetLiveGameIdByTableAction())(gameTableId: 0);
})->expectException(DragonTigerGameNoLiveGameException::class);
