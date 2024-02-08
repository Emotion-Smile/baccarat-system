<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameRoundNumberIsDuplicatedAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;

test('it can check dragon tiger game duplicate round number', function () {

    $isDuplicated = (new DragonTigerGameRoundNumberIsDuplicatedAction())(gameTableId: 1, round: 1);
    expect($isDuplicated)->toBeFalse();

    $dragonTigerGame = DragonTigerGame::factory(['game_table_id' => 1, 'round' => 4])->create();
    $isDuplicated = (new DragonTigerGameRoundNumberIsDuplicatedAction())(gameTableId: 1, round: 4);
    $dragonTigerGame->delete();
    expect($isDuplicated)->toBeTrue();


    DragonTigerGame::factory(['game_table_id' => 1, 'round' => 4, 'created_at' => now()->subDay()])->create();
    $isDuplicated = (new DragonTigerGameRoundNumberIsDuplicatedAction())(gameTableId: 1, round: 4);

    expect($isDuplicated)->toBeFalse();

});
