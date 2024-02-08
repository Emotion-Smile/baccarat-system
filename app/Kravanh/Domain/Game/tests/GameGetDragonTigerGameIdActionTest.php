<?php

use App\Kravanh\Domain\Game\Actions\GameGetDragonTigerGameIdAction;
use App\Kravanh\Domain\Game\tests\GameTestHelper;

test('it can get dragon tiger game id', function () {
    GameTestHelper::createDragonTigerGame();
    $id = (new GameGetDragonTigerGameIdAction())();
    expect($id)->toBeInt();
});
