<?php

use App\Kravanh\Domain\Game\Actions\GameGetByNameAction;
use App\Kravanh\Domain\Game\Models\Game;
use App\Kravanh\Domain\Game\Supports\GameName;
use App\Kravanh\Domain\Game\tests\GameTestHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;

test("it will throw model not found if game doesn't exist", function () {
    (new GameGetByNameAction())(GameName::DragonTiger);
})->expectException(ModelNotFoundException::class);

test('it can get a game by game name', function () {

    GameTestHelper::createDragonTigerGame();
    $game = (new GameGetByNameAction())(GameName::DragonTiger);
    expect($game)->toBeInstanceOf(Game::class);
});
