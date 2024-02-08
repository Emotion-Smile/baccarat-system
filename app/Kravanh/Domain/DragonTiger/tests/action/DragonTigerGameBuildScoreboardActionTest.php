<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetTodayResultAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;

test('test scoreboard construction from dragon tiger game results', function () {

    $results = ['tiger', 'dragon', 'dragon', 'cancel', 'tie', 'tie', 'tiger', 'dragon', 'dragon', 'dragon', 'dragon', 'dragon', 'dragon', 'dragon'];
    $resultsForTest = [
        ['tiger'],
        ['dragon', 'dragon'],
        ['cancel'],
        ['tie', 'tie'],
        ['tiger'],
        ['dragon', 'dragon', 'dragon', 'dragon', 'dragon', 'dragon'],
        ['dragon'],
    ];
    $emptyRowCountForTest = [5, 4, 5, 4, 5, 0, 5];

    foreach ($results as $result) {
        DragonTigerGame::factory(['game_table_id' => 1, 'winner' => $result])->create();
    }

    $gameResult = (new DragonTigerGameGetTodayResultAction())(gameTableId: 1);

    expect($gameResult->first())->toHaveKeys(['id', 'winner', 'number', 'round'])
        ->and(count($gameResult->toScoreboard()))->toBe(7);

    collect($gameResult->toScoreboard())->each(fn ($item) => expect(count($item))->toBe(6));

    $scoreboard = collect($gameResult->toScoreboard());
    foreach ($scoreboard as $index => $score) {
        $emptyRowCount = collect($score)->filter(fn ($item) => empty($item))->count();
        expect($emptyRowCount)->toBe($emptyRowCountForTest[$index]);
        foreach ($score as $subIndex => $item) {

            if (! isset($item['result'])) {
                continue;
            }
            expect($item['result'])->toBe($resultsForTest[$index][$subIndex]);
        }
    }

    $scoreboardCount = $gameResult->toScoreboardCount();
    expect($scoreboardCount)->toMatchArray([
        'tiger' => 2,
        'dragon' => 9,
        'cancel' => 1,
        'tie' => 2,
    ]);
});
