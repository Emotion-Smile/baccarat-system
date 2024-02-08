<?php

use App\Kravanh\Domain\Game\Actions\GameTableGetByGameIdAction;
use App\Kravanh\Domain\Game\Models\GameTable;
use Illuminate\Support\Collection;

it('returns game table data when game id exists', function () {
    GameTable::factory()->create(['game_id' => 1]);
    $result = (new GameTableGetByGameIdAction())(1);
    expect($result)->toBeInstanceOf(Collection::class)->toHaveCount(1);
});

it('returns empty collection when game id does not exist', function () {
    $result = (new GameTableGetByGameIdAction())(1);
    expect($result)->toBeInstanceOf(Collection::class)->toHaveCount(0);
});

it('returns only active game tables', function () {
    GameTable::factory()->create(['game_id' => 1, 'active' => true]);
    GameTable::factory()->create(['game_id' => 1, 'active' => false]);
    $result = (new GameTableGetByGameIdAction())(1);
    expect($result)->toBeInstanceOf(Collection::class)->toHaveCount(1);
});

it('returns expected fields', function () {
    GameTable::factory()->create(['game_id' => 1, 'active' => true]);

    $result = (new GameTableGetByGameIdAction())(1)->first();

    expect($result)->toHaveKeys(['id', 'game_id', 'label', 'stream_url']);
});
