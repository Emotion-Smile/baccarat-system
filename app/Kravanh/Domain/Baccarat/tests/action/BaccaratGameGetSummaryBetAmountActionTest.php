<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetSummaryBetAmountAction;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;

test('Get summary bet amount of dragon tiger game', function () {

    $game = BaccaratGame::factory()->liveGame()->create();

    BaccaratTicket::factory(['dragon_tiger_game_id' => $game->id])->count(10)->create();

    $data = (new BaccaratGameGetSummaryBetAmountAction())($game->id);
    expect($data)->toBeArray();
});
