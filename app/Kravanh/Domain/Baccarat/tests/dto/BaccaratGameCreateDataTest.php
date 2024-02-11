<?php


use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameCreateData;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameUnauthorizedToCreateNewGameException;
use App\Kravanh\Domain\Baccarat\Support\RoundMode;
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

test('it will throwable BaccaratGameUnauthorizedToCreateNewGame if user not a dragon tiger trader', function () {

    BaccaratGameCreateData::fromRequest(mockRequest(User::factory()->create()));

})->expectException(BaccaratGameUnauthorizedToCreateNewGameException::class);

test('it can make dragon tiger game data', function () {

    $trader = Trader::factory()->dragonTigerTrader()->create();
    $gameData = BaccaratGameCreateData::fromRequest(mockRequest($trader));

    expect($gameData)->toBeInstanceOf(BaccaratGameCreateData::class)
        ->and($gameData->gameTableId)->toBe($trader->group_id)
        ->and($gameData->userId)->toBe($trader->id)
        ->and($gameData->round)->toBe(1)
        ->and($gameData->number)->toBe(1);
});
