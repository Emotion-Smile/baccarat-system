<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameHasLiveGameAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;

test('it can check dragon tiger has live game now', function () {

    $isLive = (new DragonTigerGameHasLiveGameAction())(gameTableId: 1);
    expect($isLive)->toBeFalse();

    DragonTigerGame::factory(['game_table_id' => 1])->create();

    $isLive = (new DragonTigerGameHasLiveGameAction())(gameTableId: 1);
    expect($isLive)->toBeFalse();

    DragonTigerGame::factory(['game_table_id' => 1])->liveGame()->create();

    $isLive = (new DragonTigerGameHasLiveGameAction())(gameTableId: 1);
    expect($isLive)->toBeTrue();

    $isLive = (new DragonTigerGameHasLiveGameAction())(gameTableId: [1, 2]);
    expect($isLive)->toBeTrue();

});
