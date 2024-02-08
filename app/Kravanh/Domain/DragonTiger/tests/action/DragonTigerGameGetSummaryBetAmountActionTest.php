<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetSummaryBetAmountAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;

test('Get summary bet amount of dragon tiger game', function () {

    $game = DragonTigerGame::factory()->liveGame()->create();

    DragonTigerTicket::factory(['dragon_tiger_game_id' => $game->id])->count(10)->create();

    $data = (new DragonTigerGameGetSummaryBetAmountAction())($game->id);
    expect($data)->toBeArray();
});
