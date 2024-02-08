<?php

use App\Kravanh\Domain\Game\Dto\GameTableConditionData as Condition;
use App\Kravanh\Domain\Game\tests\GameTestHelper;

test('it can build condition from array correctly', function () {

    $conditionBuild = Condition::build(GameTestHelper::fakeConditionArray(redBlackMinBetPerTicker: 3));

    $conditionData = Condition::fromBuild(
        gameId: 1,
        gameTableId: 2,
        userId: 3,
        userType: 'test',
        build: $conditionBuild
    );


    expect($conditionBuild)->toBeArray()
        ->and($conditionBuild)->toHaveKeys(['is_allowed', 'share_and_commission', 'bet_condition'])
        ->and($conditionData->gameId)->toBe(1)
        ->and($conditionData->gameTableId)->toBe(2)
        ->and($conditionData->userId)->toBe(3)
        ->and($conditionData->userType)->toBe('test')
        ->and($conditionData->isAllowed)->toBeTrue()
        ->and($conditionData->getShare())->toBe(95)
        ->and($conditionData->getUplineShare())->toBe(5)
        ->and($conditionData->getCommission())->toBe(0.001)
        ->and($conditionData->getGameLimit())->toBe(1000)
        ->and($conditionData->getWinLimitPerDay())->toBe(2000)
        ->and($conditionData->getDragonTigerMinBetPerTicket())->toBe(1)
        ->and($conditionData->getDragonTigerMaxBetPerTicket())->toBe(500)
        ->and($conditionData->getTieMinBetPerTicket())->toBe(1)
        ->and($conditionData->getTieMaxBetPerTicket())->toBe((int)(floor($conditionData->getDragonTigerMaxBetPerTicket() / Condition::TIE_PAYOUT_RATE)))
        ->and($conditionData->getRedBlackMinBetPerTicket())->toBe(3)
        ->and($conditionData->getRedBlackMaxBetPerTicket())->toBe(400);

});
