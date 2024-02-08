<?php

use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameSubmitResultData;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerCardException;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerCard;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerGameWinner;
use App\Kravanh\Domain\User\Models\Trader;

test('it can create dragon tiger game submit result data', function () {

    $trader = Trader::factory()->dragonTigerTrader()->create();

    $data = DragonTigerGameSubmitResultData::make(
        user: $trader,
        dragonTigerGameId: 1,
        dragonResult: 1,
        dragonType: DragonTigerCard::Heart,
        tigerResult: 8,
        tigerType: DragonTigerCard::Club
    );

    expect($data->user->isTraderDragonTiger())->toBeTrue()
        ->and($data->dragonTigerGameId)->toBe(1)
        ->and($data->dragonResult)->toBe(1)
        ->and($data->dragonType)->toBe(DragonTigerCard::Heart)
        ->and($data->dragonCard->range())->toBe(DragonTigerCard::Small)
        ->and($data->dragonCard->color())->toBe(DragonTigerCard::Red)
        ->and($data->tigerResult)->toBe(8)
        ->and($data->tigerType)->toBe(DragonTigerCard::Club)
        ->and($data->tigerCard->range())->toBe(DragonTigerCard::Big)
        ->and($data->tigerCard->color())->toBe(DragonTigerCard::Black)
        ->and($data->winner())->toBe(DragonTigerGameWinner::Tiger);

});

test('ensure winner', function () {

    $trader = Trader::factory()->dragonTigerTrader()->create();

    $data = DragonTigerGameSubmitResultData::make(
        user: $trader,
        dragonTigerGameId: 1,
        dragonResult: 8,
        dragonType: DragonTigerCard::Heart,
        tigerResult: 8,
        tigerType: DragonTigerCard::Club
    );

    expect($data->winner())->toBe(DragonTigerGameWinner::Tie);

    $data = DragonTigerGameSubmitResultData::make(
        user: $trader,
        dragonTigerGameId: 1,
        dragonResult: 10,
        dragonType: DragonTigerCard::Heart,
        tigerResult: 2,
        tigerType: DragonTigerCard::Club
    );

    expect($data->winner())->toBe(DragonTigerGameWinner::Dragon);

});

test('ensure card type and range number are valid', function () {
    $trader = Trader::factory()->dragonTigerTrader()->create();

    try {
        DragonTigerGameSubmitResultData::make(
            user: $trader,
            dragonTigerGameId: 1,
            dragonResult: 8,
            dragonType: 'unknown',
            tigerResult: 8,
            tigerType: DragonTigerCard::Club
        );
    } catch (Exception $e) {
        expect($e->getMessage())->toBe(DragonTigerCardException::invalidType()->getMessage());
    }

    try {
        DragonTigerGameSubmitResultData::make(
            user: $trader,
            dragonTigerGameId: 1,
            dragonResult: 13,
            dragonType: DragonTigerCard::Heart,
            tigerResult: 0,
            tigerType: DragonTigerCard::Club
        );
    } catch (Exception $e) {
        expect($e->getMessage())->toBe(DragonTigerCardException::invalidRange()->getMessage());
    }

});


