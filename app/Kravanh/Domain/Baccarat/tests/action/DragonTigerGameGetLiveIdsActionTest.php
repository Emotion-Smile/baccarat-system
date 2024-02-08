<?php


use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetLiveIdsAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;

test('it can get live game', function () {

    DragonTigerGame::factory()->create();
    $liveGameIds = (new DragonTigerGameGetLiveIdsAction())();

    expect($liveGameIds)->toBeArray()
        ->and($liveGameIds)->toBeEmpty();

    DragonTigerGame::factory()->liveGame()->create();
    $liveGameIds = (new DragonTigerGameGetLiveIdsAction())();

    expect(count($liveGameIds))->toBe(1)
        ->and(DragonTigerGame::count())->toBe(2);

});
