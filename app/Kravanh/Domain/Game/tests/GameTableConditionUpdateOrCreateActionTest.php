<?php

use App\Kravanh\Domain\Game\Actions\GameTableConditionUpdateOrCreateAction;
use App\Kravanh\Domain\Game\Dto\GameTableConditionData;
use App\Kravanh\Domain\Game\tests\GameTestHelper;

use function Pest\Laravel\assertDatabaseCount;

test('it can create game table condition', function () {

    $gameTableCondition = (new GameTableConditionUpdateOrCreateAction())(GameTestHelper::fakeConditionData());

    $condition = GameTableConditionData::fromDatabase($gameTableCondition->refresh());

    assertDatabaseCount('game_table_conditions', 1);

    expect($condition->gameId)->toBe(1)
        ->and($condition->gameTableId)->toBe(2)
        ->and($condition->userId)->toBe(3)
        ->and($condition->userType)->toBe('super_senior')
        ->and($condition->isAllowed)->toBeTrue()
        ->and($condition->getShare())->toBe(95)
        ->and($condition->getUplineShare())->toBe(5)
        ->and($condition->getCommission())->toBe(0.001)
        ->and($condition->getGameLimit())->toBe(1000)
        ->and($condition->getWinLimitPerDay())->toBe(2000)
        ->and($condition->getDragonTigerMinBetPerTicket())->toBe(1)
        ->and($condition->getDragonTigerMaxBetPerTicket())->toBe(500)
        ->and($condition->getTieMinBetPerTicket())->toBe(1)
        ->and($condition->getTieMaxBetPerTicket())->toBe(
            (int) (floor($condition->getDragonTigerMaxBetPerTicket() / GameTableConditionData::TIE_PAYOUT_RATE))
        )
        ->and($condition->getRedBlackMinBetPerTicket())->toBe(1)
        ->and($condition->getRedBlackMaxBetPerTicket())->toBe(400);
});

test('it can update game table condition', function () {

    (new GameTableConditionUpdateOrCreateAction())(GameTestHelper::fakeConditionData());

    $fakeUpdateShareCom = [
        GameTableConditionData::ORIGINAL_UPLINE_SHARE => 100,
        GameTableConditionData::IS_ALLOWED => true,
        GameTableConditionData::SHARE => 90,
        GameTableConditionData::UP_LINE_SHARE => 10,
        GameTableConditionData::COMMISSION => 0.11,
        GameTableConditionData::MATCH_LIMIT => 1001,
        GameTableConditionData::WIN_LIMIT_PER_DAY => 2001,
        GameTableConditionData::DRAGON_TIGER_MIN_BET_PER_TICKET => 11,
        GameTableConditionData::DRAGON_TIGER_MAX_BET_PER_TICKET => 501,
        GameTableConditionData::TIE_MIN_BET_PER_TICKET => 21,
        GameTableConditionData::TIE_MAX_BET_PER_TICKET => 201,
        GameTableConditionData::RED_BLACK_MIN_BET_PER_TICKET => 31,
        GameTableConditionData::RED_BLACK_MAX_BET_PER_TICKET => 401,
    ];

    $data = GameTestHelper::fakeConditionData(
        isAllowed: false,
        shareComAndCondition: GameTableConditionData::build($fakeUpdateShareCom)
    );

    $gameTableCondition = (new GameTableConditionUpdateOrCreateAction())($data);
    $condition = GameTableConditionData::fromDatabase($gameTableCondition);

    expect($condition->gameId)->toBe(1)
        ->and($condition->gameTableId)->toBe(2)
        ->and($condition->userId)->toBe(3)
        ->and($condition->userType)->toBe('super_senior')
        ->and($condition->isAllowed)->toBeFalse()
        ->and($condition->getShare())->toBe(90)
        ->and($condition->getUplineShare())->toBe(10)
        ->and($condition->getCommission())->toBe(0.11)
        ->and($condition->getGameLimit())->toBe(1001)
        ->and($condition->getWinLimitPerDay())->toBe(2001)
        ->and($condition->getDragonTigerMinBetPerTicket())->toBe(11)
        ->and($condition->getDragonTigerMaxBetPerTicket())->toBe(501)
        ->and($condition->getTieMinBetPerTicket())->toBe(21)
        ->and($condition->getTieMaxBetPerTicket())->toBe(201)
        ->and($condition->getRedBlackMinBetPerTicket())->toBe(31)
        ->and($condition->getRedBlackMaxBetPerTicket())->toBe(401);

});
