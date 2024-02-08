<?php

use App\Kravanh\Domain\Game\Actions\UserCanPlayDragonTigerGameAction;
use App\Kravanh\Domain\Game\Models\GameTableCondition;
use App\Kravanh\Domain\Game\tests\GameTestHelper;

test('ensure user can play dragon tiger game', function () {

    $game = GameTestHelper::createDragonTigerGame();
    $canPlay = (new UserCanPlayDragonTigerGameAction())(1);
    expect($canPlay)->toBeFalse();

    GameTableCondition::create([
        'game_id' => $game->id,
        'game_table_id' => 1,
        'user_id' => 1,
        'user_type' => 'member',
        'is_allowed' => 1,
        'share_and_commission' => [],
        'bet_condition' => []
    ]);

    $canPlay = (new UserCanPlayDragonTigerGameAction())(1);
    expect($canPlay)->toBeTrue();

});
