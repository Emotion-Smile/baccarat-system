<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameSubmitCancelResultAction;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\User\Models\Trader;

test('verify cancel result submitting of Dragon Tiger game', function () {

    $trader = Trader::factory()->dragonTigerTrader()->create();
    $game = BaccaratGame::factory(['game_table_id' => $trader->getGameTableId()])->liveGame()->create();

    (new BaccaratGameSubmitCancelResultAction())($game->id, $trader->id);

    expect($game->refresh()->isCancel())->toBeTrue()
        ->and($game->result_submitted_user_id)->toBe($trader->id);

});
