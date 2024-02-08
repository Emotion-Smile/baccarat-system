<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameSubmitCancelResultAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\User\Models\Trader;

test('verify cancel result submitting of Dragon Tiger game', function () {

    $trader = Trader::factory()->dragonTigerTrader()->create();
    $game = DragonTigerGame::factory(['game_table_id' => $trader->getGameTableId()])->liveGame()->create();

    (new DragonTigerGameSubmitCancelResultAction())($game->id, $trader->id);

    expect($game->refresh()->isCancel())->toBeTrue()
        ->and($game->result_submitted_user_id)->toBe($trader->id);

});
