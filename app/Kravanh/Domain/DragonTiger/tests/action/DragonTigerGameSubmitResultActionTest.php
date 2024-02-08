<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameCreateAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameSubmitResultAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameCreateData;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameSubmitResultData;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerCard;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerGameWinner;
use App\Kravanh\Domain\User\Models\Trader;
use Carbon\Carbon;

test('verify submission of dragon tiger game results', function () {

    $trader = Trader::factory()->dragonTigerTrader()->create();
    $createData = DragonTigerGameCreateData::from(user: $trader);
    $game1 = (new DragonTigerGameCreateAction())($createData)->refresh();

    expect($game1->isLive())->toBeTrue();

    $submitData = DragonTigerGameSubmitResultData::make(
        user: $trader,
        dragonTigerGameId: $game1->id,
        dragonResult: 8,
        dragonType: DragonTigerCard::Heart,
        tigerResult: 6,
        tigerType: DragonTigerCard::Club
    );

    Carbon::setTestNow(now()->addMinute());
    $isOk = (new DragonTigerGameSubmitResultAction())($submitData);

    $game1->refresh();

    expect(! $game1->isLive())->toBeTrue()
        ->and($isOk)->toBeTrue()
        ->and($game1->result_submitted_user_id)->toBe($submitData->user->id)
        ->and($game1->result_submitted_at)->not()->toBeNull()
        ->and($game1->dragon_result)->toBe($submitData->dragonResult)
        ->and($game1->dragon_type)->toBe($submitData->dragonType)
        ->and($game1->dragon_color)->toBe(DragonTigerCard::Red)
        ->and($game1->dragon_range)->toBe(DragonTigerCard::Big)
        ->and($game1->tiger_result)->toBe($submitData->tigerResult)
        ->and($game1->tiger_type)->toBe($submitData->tigerType)
        ->and($game1->tiger_color)->toBe(DragonTigerCard::Black)
        ->and($game1->tiger_range)->toBe(DragonTigerCard::Small)
        ->and($game1->winner)->toBe(DragonTigerGameWinner::Dragon);
});

test(/**
 * @throws Throwable
 * @throws \App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameSubmitResultBetOpenException
 */ 'it handles failure in submitting results for dragon tiger game', function () {

    $trader = Trader::factory()->dragonTigerTrader()->create();
    $createData = DragonTigerGameCreateData::from(user: $trader);
    $game1 = (new DragonTigerGameCreateAction())($createData)->refresh();

    expect($game1->isLive())->toBeTrue();

    $submitData = DragonTigerGameSubmitResultData::make(
        user: $trader,
        dragonTigerGameId: 0,
        dragonResult: 8,
        dragonType: DragonTigerCard::Heart,
        tigerResult: 6,
        tigerType: DragonTigerCard::Club
    );

    $isOk = (new DragonTigerGameSubmitResultAction())($submitData);

    expect($isOk)->toBeFalse();
})->skip();
