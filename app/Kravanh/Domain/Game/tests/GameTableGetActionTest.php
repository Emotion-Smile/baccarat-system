<?php

use App\Kravanh\Domain\Game\Actions\GameTableGetAction;
use App\Kravanh\Domain\Game\Models\GameTable;

test('it can get game table', function () {
    $gameTable = GameTable::factory(['stream_url' => 'https://youtube.com'])->create();
    $table = (new GameTableGetAction())($gameTable->id);
    expect($table->stream_url)->toBe('https://youtube.com')
        ->and($table->toArray())->toHaveKeys(['id', 'game_id', 'label', 'stream_url']);
});
