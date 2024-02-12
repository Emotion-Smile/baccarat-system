<?php


use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetLiveIdsAction;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;

test('it can get live game', function () {

    BaccaratGame::factory()->create();
    $liveGameIds = (new BaccaratGameGetLiveIdsAction())();

    expect($liveGameIds)->toBeArray()
        ->and($liveGameIds)->toBeEmpty();

    BaccaratGame::factory()->liveGame()->create();
    $liveGameIds = (new BaccaratGameGetLiveIdsAction())();

    expect(count($liveGameIds))->toBe(1)
        ->and(BaccaratGame::count())->toBe(2);

});
