<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameCreateAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameSubmitResultAction;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameCreateData;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameSubmitResultData;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard;
use App\Kravanh\Domain\Baccarat\Support\BaccaratGameWinner;
use App\Kravanh\Domain\User\Models\Trader;
use Carbon\Carbon;

test('verify submission of dragon tiger game results', function () {

    $trader = Trader::factory()->baccaratTrader()->create();
    $createData = BaccaratGameCreateData::from(user: $trader);
    $game1 = (new BaccaratGameCreateAction())($createData)->refresh();

    expect($game1->isLive())->toBeTrue();

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
    $isOk = (new BaccaratGameSubmitResultAction())($submitData);

    $game1->refresh();

    expect(! $game1->isLive())->toBeTrue()
        ->and($isOk)->toBeTrue()
        ->and($game1->result_submitted_user_id)->toBe($submitData->user->id)
        ->and($game1->result_submitted_at)->not()->toBeNull()
        ->and($game1->dragon_result)->toBe($submitData->dragonResult)
        ->and($game1->dragon_type)->toBe($submitData->dragonType)
        ->and($game1->dragon_color)->toBe(BaccaratCard::Red)
        ->and($game1->dragon_range)->toBe(BaccaratCard::Big)
        ->and($game1->tiger_result)->toBe($submitData->tigerResult)
        ->and($game1->tiger_type)->toBe($submitData->tigerType)
        ->and($game1->tiger_color)->toBe(BaccaratCard::Black)
        ->and($game1->tiger_range)->toBe(BaccaratCard::Small)
        ->and($game1->winner)->toBe(BaccaratGameWinner::Player);
});

test(/**
 * @throws Throwable
 * @throws \App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameSubmitResultBetOpenException
 */ 'it handles failure in submitting results for dragon tiger game', function () {

    $trader = Trader::factory()->dragonTigerTrader()->create();
    $createData = BaccaratGameCreateData::from(user: $trader);
    $game1 = (new BaccaratGameCreateAction())($createData)->refresh();

    expect($game1->isLive())->toBeTrue();

    $submitData = BaccaratGameSubmitResultData::make(
        user: $trader,
//        dragonTigerGameId: 0,
//        dragonResult: 8,
//        dragonType: BaccaratCard::Heart,
//        tigerResult: 6,
//        tigerType: BaccaratCard::Club
        baccaratGameId: 0,
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

    $isOk = (new BaccaratGameSubmitResultAction())($submitData);

    expect($isOk)->toBeFalse();
})->skip();
