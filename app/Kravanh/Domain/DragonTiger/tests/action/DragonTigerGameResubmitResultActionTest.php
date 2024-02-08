<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameCreateAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameResubmitResultAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameSubmitResultAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameCreateData;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameSubmitResultData;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameResubmitResultOnLiveException;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerCard;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerGameWinner;
use App\Kravanh\Domain\User\Models\Trader;
use Carbon\Carbon;

test('verify resubmit of dragon tiger game results', function () {

    $trader = Trader::factory()->dragonTigerTrader()->create();
    $createData = DragonTigerGameCreateData::from(user: $trader);

    $game1 = (new DragonTigerGameCreateAction())($createData)->refresh();

    $submitData = DragonTigerGameSubmitResultData::make(
        user: $trader,
        dragonTigerGameId: $game1->id,
        dragonResult: 8,
        dragonType: DragonTigerCard::Heart,
        tigerResult: 6,
        tigerType: DragonTigerCard::Club
    );

    Carbon::setTestNow(now()->addMinute());

    (new DragonTigerGameSubmitResultAction())($submitData);

    $resubmitData = DragonTigerGameSubmitResultData::make(
        user: $trader,
        dragonTigerGameId: $game1->id,
        dragonResult: 5,
        dragonType: DragonTigerCard::Spade,
        tigerResult: 7,
        tigerType: DragonTigerCard::Diamond
    );

    (new DragonTigerGameResubmitResultAction())($resubmitData);

    $game1->refresh();

    expect(! $game1->isLive())->toBeTrue()
        ->and($game1->dragon_result)->toBe($resubmitData->dragonResult)
        ->and($game1->dragon_type)->toBe($resubmitData->dragonType)
        ->and($game1->tiger_result)->toBe($resubmitData->tigerResult)
        ->and($game1->tiger_type)->toBe($resubmitData->tigerType)
        ->and($game1->winner)->toBe(DragonTigerGameWinner::Tiger);
});

test('it throws exception when resubmitting results on a live dragon tiger game', function () {

    $trader = Trader::factory()->dragonTigerTrader()->create();
    $createData = DragonTigerGameCreateData::from(user: $trader);

    $game1 = (new DragonTigerGameCreateAction())($createData)->refresh();

    $submitData = DragonTigerGameSubmitResultData::make(
        user: $trader,
        dragonTigerGameId: $game1->id,
        dragonResult: 8,
        dragonType: DragonTigerCard::Heart,
        tigerResult: 6,
        tigerType: DragonTigerCard::Club
    );

    (new DragonTigerGameResubmitResultAction())($submitData);

})->expectException(DragonTigerGameResubmitResultOnLiveException::class);
