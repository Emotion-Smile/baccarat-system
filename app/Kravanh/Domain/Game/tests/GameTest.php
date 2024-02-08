<?php


use App\Kravanh\Domain\Game\Actions\GameDragonTigerGetAction;
use App\Kravanh\Domain\Game\Models\Game;
use App\Kravanh\Domain\Game\Models\GameTable;
use App\Kravanh\Domain\Game\Supports\GameName;
use App\Kravanh\Domain\Game\Supports\GameType;
use App\Kravanh\Domain\Game\tests\GameTestHelper;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

test("it can get dragon tiger game", function () {


    GameTestHelper::createDragonTigerGame();

    $game = app(GameDragonTigerGetAction::class)();

    expect($game)->toBeInstanceOf(Game::class)
        ->and($game->name)->toBe(GameName::DragonTiger->value)
        ->and($game->type)->toBe(GameType::Casino->value);

});

test("it will throw model not found if first table of the game doesn't exist", function () {

    GameTestHelper::createDragonTigerGame();
    app(GameDragonTigerGetAction::class)()->firstTable();

})->expectException(ModelNotFoundException::class);


test('it can get first table of dragon tiger game', function () {

    GameTestHelper::createDragonTigerGameWithDefaultTable();

    $game = app(GameDragonTigerGetAction::class)();
    $table = $game->firstTable(columns: ['id']);

    expect($game)->toBeInstanceOf(Game::class)
        ->and($table)->toBeInstanceOf(GameTable::class)
        ->and(array_key_exists('id', $table->getAttributes()))->toBeTrue();

});

test('it user present', function () {
    $user = new User();
    expect($user->isUserPresent())->toBeFalse();
});

