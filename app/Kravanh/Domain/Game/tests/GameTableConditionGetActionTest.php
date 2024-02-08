<?php

use App\Kravanh\Domain\Game\Actions\GameTableConditionGetAction;
use App\Kravanh\Domain\Game\Actions\GameTableConditionUpdateOrCreateAction;
use App\Kravanh\Domain\Game\Dto\GameTableConditionData;
use App\Kravanh\Domain\Game\tests\GameTestHelper;

test('it will throw ModelNotFoundException if the user don\'t have game table condition', function () {
    $condition = app(GameTableConditionGetAction::class)(gameId: 1, gameTableId: 1, userId: 1);
    expect($condition)->toBeInstanceOf(GameTableConditionData::class)
        ->and($condition->gameId)->toBe(1)
        ->and($condition->gameTableId)->toBe(1)
        ->and($condition->userId)->toBe(1)
        ->and($condition->userType)->toBe('unknown');
});

test('it can get game table condition belong to user', function () {

    $condition = (new GameTableConditionUpdateOrCreateAction())(
        GameTestHelper::fakeConditionData()
    );

    $userGameCondition = (new GameTableConditionGetAction())(
        gameId: $condition->game_id,
        gameTableId: $condition->game_table_id,
        userId: $condition->user_id
    );

    expect($userGameCondition->gameId)
        ->toBe($condition->game_id)
        ->and($userGameCondition->gameTableId)->toBe($condition->game_table_id)
        ->and($userGameCondition->userId)->toBe($condition->user_id)
        ->and($userGameCondition->userType)->toBe($condition->user_type)
        ->and($userGameCondition->isAllowed)->toBe($condition->is_allowed)
        ->and($userGameCondition->getShare())->toBe($condition->share_and_commission['share'])
        ->and($userGameCondition->getUplineShare())->toBe($condition->share_and_commission['upline_share'])
        ->and($userGameCondition->getCommission())->toBe($condition->share_and_commission['commission'])
        ->and($userGameCondition->getGameLimit())->toBe($condition->bet_condition['match_limit'])
        ->and($userGameCondition->getWinLimitPerDay())->toBe($condition->bet_condition['win_limit_per_day'])
        ->and($userGameCondition->getTieMinBetPerTicket())->toBe($condition->bet_condition['tie_min_bet_per_ticket'])
        ->and($userGameCondition->getTieMaxBetPerTicket())->toBe($condition->bet_condition['tie_max_bet_per_ticket'])
        ->and($userGameCondition->getRedBlackMinBetPerTicket())->toBe($condition->bet_condition['red_black_min_bet_per_ticket'])
        ->and($userGameCondition->getRedBlackMaxBetPerTicket())->toBe($condition->bet_condition['red_black_max_bet_per_ticket'])
        ->and($userGameCondition->getDragonTigerMinBetPerTicket())->toBe($condition->bet_condition['dragon_tiger_min_bet_per_ticket'])
        ->and($userGameCondition->getDragonTigerMaxBetPerTicket())->toBe($condition->bet_condition['dragon_tiger_max_bet_per_ticket']);
});
