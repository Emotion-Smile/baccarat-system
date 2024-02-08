<?php


use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameCreateData;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameUnauthorizedToCreateNewGameException;
use App\Kravanh\Domain\DragonTiger\Support\RoundMode;
use App\Kravanh\Domain\User\Models\Trader;
use App\Models\User;
use Illuminate\Http\Request;

function mockRequest(User $user)
{
    $request = mock(Request::class);
    $request->shouldReceive('user')->andReturn($user);
    $request->shouldReceive('get')->andReturn(RoundMode::LastRound);

    return $request;
}

test('it will throwable DragonTigerGameUnauthorizedToCreateNewGame if user not a dragon tiger trader', function () {

    DragonTigerGameCreateData::fromRequest(mockRequest(User::factory()->create()));

})->expectException(DragonTigerGameUnauthorizedToCreateNewGameException::class);

test('it can make dragon tiger game data', function () {

    $trader = Trader::factory()->dragonTigerTrader()->create();
    $gameData = DragonTigerGameCreateData::fromRequest(mockRequest($trader));

    expect($gameData)->toBeInstanceOf(DragonTigerGameCreateData::class)
        ->and($gameData->gameTableId)->toBe($trader->group_id)
        ->and($gameData->userId)->toBe($trader->id)
        ->and($gameData->round)->toBe(1)
        ->and($gameData->number)->toBe(1);
});
