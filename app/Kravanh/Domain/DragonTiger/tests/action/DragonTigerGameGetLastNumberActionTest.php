<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetLastNumberAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\Game\Models\GameTable;
use Carbon\Carbon;

test('it can get dragon tiger game last number', function () {

    $lastHandNumber = (new DragonTigerGameGetLastNumberAction())(gameTableId: 1, round: 1);

    expect($lastHandNumber)->toBeInt()
        ->and($lastHandNumber)->toBe(0);

    $gameTable = GameTable::factory(['game_id' => 1])->create();

    DragonTigerGame::factory(['game_table_id' => $gameTable->id])
        ->count(3)
        ->create();

    $lastHandNumber = (new DragonTigerGameGetLastNumberAction())(gameTableId: $gameTable->id, round: 1);
    expect($lastHandNumber)->toBe(3);

    $lastHandNumber = (new DragonTigerGameGetLastNumberAction())(gameTableId: $gameTable->id, round: 1);
    $lastHandNumberRoundTwo = (new DragonTigerGameGetLastNumberAction())(gameTableId: $gameTable->id, round: 2);

    expect($lastHandNumber)->toBe(3)
        ->and($lastHandNumberRoundTwo)->toBe(0);

    DragonTigerGame::factory(['game_table_id' => $gameTable->id, 'round' => 2, 'number' => 1])->create();

    $lastHandNumberRoundTwo = (new DragonTigerGameGetLastNumberAction())(gameTableId: $gameTable->id, round: 2);
    expect($lastHandNumberRoundTwo)->toBe(1);

    Carbon::setTestNow(now()->addDay());

    $lastHandNumberRoundTwo = (new DragonTigerGameGetLastNumberAction())(gameTableId: $gameTable->id, round: 1);
    expect($lastHandNumberRoundTwo)->toBe(0);
});
