<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetLastNumberAction;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Game\Models\GameTable;
use Carbon\Carbon;

test('it can get dragon tiger game last number', function () {

    $lastHandNumber = (new BaccaratGameGetLastNumberAction())(gameTableId: 1, round: 1);

    expect($lastHandNumber)->toBeInt()
        ->and($lastHandNumber)->toBe(0);

    $gameTable = GameTable::factory(['game_id' => 1])->create();

    BaccaratGame::factory(['game_table_id' => $gameTable->id])
        ->count(3)
        ->create();

    $lastHandNumber = (new BaccaratGameGetLastNumberAction())(gameTableId: $gameTable->id, round: 1);
    expect($lastHandNumber)->toBe(3);

    $lastHandNumber = (new BaccaratGameGetLastNumberAction())(gameTableId: $gameTable->id, round: 1);
    $lastHandNumberRoundTwo = (new BaccaratGameGetLastNumberAction())(gameTableId: $gameTable->id, round: 2);

    expect($lastHandNumber)->toBe(3)
        ->and($lastHandNumberRoundTwo)->toBe(0);

    BaccaratGame::factory(['game_table_id' => $gameTable->id, 'round' => 2, 'number' => 1])->create();

    $lastHandNumberRoundTwo = (new BaccaratGameGetLastNumberAction())(gameTableId: $gameTable->id, round: 2);
    expect($lastHandNumberRoundTwo)->toBe(1);

    Carbon::setTestNow(now()->addDay());

    $lastHandNumberRoundTwo = (new BaccaratGameGetLastNumberAction())(gameTableId: $gameTable->id, round: 1);
    expect($lastHandNumberRoundTwo)->toBe(0);
});
