<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetTodayResultAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;

test('it can get all game result today', function () {

    DragonTigerGame::factory(['game_table_id' => 1])->count(10)->create();

    $gameResult = (new DragonTigerGameGetTodayResultAction())(gameTableId: 1);

    expect($gameResult->first())->toHaveKeys(['id', 'winner', 'number', 'round'])
        ->and($gameResult->count())->toBe(10);

});
