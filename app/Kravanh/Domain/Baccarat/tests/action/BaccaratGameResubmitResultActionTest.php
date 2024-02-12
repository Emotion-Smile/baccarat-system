<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameCreateAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameResubmitResultAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameSubmitResultAction;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameCreateData;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameSubmitResultData;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameResubmitResultOnLiveException;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard;
use App\Kravanh\Domain\Baccarat\Support\BaccaratGameWinner;
use App\Kravanh\Domain\User\Models\Trader;
use Carbon\Carbon;

test('verify resubmit of dragon tiger game results', function () {

    $trader = Trader::factory()->dragonTigerTrader()->create();
    $createData = BaccaratGameCreateData::from(user: $trader);

    $game1 = (new BaccaratGameCreateAction())($createData)->refresh();

    $submitData = BaccaratGameSubmitResultData::make(
        user: $trader,
//        dragonTigerGameId: $game1->id,
//        dragonResult: 8,
//        dragonType: BaccaratCard::Heart,
//        tigerResult: 6,
//        tigerType: BaccaratCard::Club
        baccaratGameId: $game1->id,
        playerFirstCardValue: 4,
        playerFirstCardType: BaccaratCard::Heart,
        playerSecondCardValue: 4,
        playerSecondCardType: BaccaratCard::Spade,
        playerThirdCardValue: 1,
        playerThirdCardType: BaccaratCard::Club,
        playerPoints: 9,
        bankerFirstCardValue: 2,
        bankerFirstCardType: BaccaratCard::Diamond,
        bankerSecondCardValue: 4,
        bankerSecondCardType: BaccaratCard::Heart,
        bankerThirdCardValue: 2,
        bankerThirdCardType: BaccaratCard::Club,
        bankerPoints: 8
    );

    Carbon::setTestNow(now()->addMinute());

    (new BaccaratGameSubmitResultAction())($submitData);

    $resubmitData = BaccaratGameSubmitResultData::make(
        user: $trader,
//        dragonTigerGameId: $game1->id,
//        dragonResult: 5,
//        dragonType: BaccaratCard::Spade,
//        tigerResult: 7,
//        tigerType: BaccaratCard::Diamond
        baccaratGameId: $game1->id,
        playerFirstCardValue: 4,
        playerFirstCardType: BaccaratCard::Heart,
        playerSecondCardValue: 4,
        playerSecondCardType: BaccaratCard::Spade,
        playerThirdCardValue: 1,
        playerThirdCardType: BaccaratCard::Club,
        playerPoints: 9,
        bankerFirstCardValue: 2,
        bankerFirstCardType: BaccaratCard::Diamond,
        bankerSecondCardValue: 4,
        bankerSecondCardType: BaccaratCard::Heart,
        bankerThirdCardValue: 2,
        bankerThirdCardType: BaccaratCard::Club,
        bankerPoints: 8
    );

    (new BaccaratGameResubmitResultAction())($resubmitData);

    $game1->refresh();

    expect(! $game1->isLive())->toBeTrue()
        ->and($game1->dragon_result)->toBe($resubmitData->dragonResult)
        ->and($game1->dragon_type)->toBe($resubmitData->dragonType)
        ->and($game1->tiger_result)->toBe($resubmitData->tigerResult)
        ->and($game1->tiger_type)->toBe($resubmitData->tigerType)
        ->and($game1->winner)->toBe(BaccaratGameWinner::Banker);
});

test('it throws exception when resubmitting results on a live dragon tiger game', function () {

    $trader = Trader::factory()->dragonTigerTrader()->create();
    $createData = BaccaratGameCreateData::from(user: $trader);

    $game1 = (new BaccaratGameCreateAction())($createData)->refresh();

    $submitData = BaccaratGameSubmitResultData::make(
        user: $trader,
//        dragonTigerGameId: $game1->id,
//        dragonResult: 8,
//        dragonType: BaccaratCard::Heart,
//        tigerResult: 6,
//        tigerType: BaccaratCard::Club
        baccaratGameId: $game1->id,
        playerFirstCardValue: 4,
        playerFirstCardType: BaccaratCard::Heart,
        playerSecondCardValue: 4,
        playerSecondCardType: BaccaratCard::Spade,
        playerThirdCardValue: 1,
        playerThirdCardType: BaccaratCard::Club,
        playerPoints: 9,
        bankerFirstCardValue: 2,
        bankerFirstCardType: BaccaratCard::Diamond,
        bankerSecondCardValue: 4,
        bankerSecondCardType: BaccaratCard::Heart,
        bankerThirdCardValue: 2,
        bankerThirdCardType: BaccaratCard::Club,
        bankerPoints: 8
    );

    (new BaccaratGameResubmitResultAction())($submitData);

})->expectException(BaccaratGameResubmitResultOnLiveException::class);
