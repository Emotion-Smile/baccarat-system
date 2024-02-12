<?php

use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameSubmitResultData;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratCardException;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard;
use App\Kravanh\Domain\Baccarat\Support\BaccaratGameWinner;
use App\Kravanh\Domain\User\Models\Trader;

test('it can create baccarat game submit result data', function () {
    $trader = Trader::factory()->baccaratTrader()->create();

    $data = BaccaratGameSubmitResultData::make(
        user: $trader,
//        dragonTigerGameId: 1,
//        dragonResult: 1,
//        dragonType: BaccaratCard::Heart,
//        tigerResult: 8,
//        tigerType: BaccaratCard::Club
        baccaratGameId: 1,
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

    expect($data->user->isTraderBaccarat())->toBeTrue()
        ->and($data->baccaratGameId)->toBe(1)
        ->and($data->playerFirstCardValue)->toBe(4)
        ->and($data->playerSecondCardValue)->toBe(4)
        ->and($data->playerThirdCardValue)->toBe(1)
        ->and($data->playerPoints)->toBe(9)
        ->and($data->bankerFirstCardValue)->toBe(2)
        ->and($data->bankerSecondCardValue)->toBe(4)
        ->and($data->bankerThirdCardValue)->toBe(2)
        ->and($data->bankerPoints)->toBe(8)
        ->and($data->winner())->toBe(in_array(BaccaratGameWinner::Player, $this->winner));

//        ->and($data->dragonTigerGameId)->toBe(1)
//        ->and($data->dragonResult)->toBe(1)
//        ->and($data->dragonType)->toBe(BaccaratCard::Heart)
//        ->and($data->dragonCard->range())->toBe(BaccaratCard::Small)
//        ->and($data->dragonCard->color())->toBe(BaccaratCard::Red)
//        ->and($data->tigerResult)->toBe(8)
//        ->and($data->tigerType)->toBe(BaccaratCard::Club)
//        ->and($data->tigerCard->range())->toBe(BaccaratCard::Big)
//        ->and($data->tigerCard->color())->toBe(BaccaratCard::Black)
//        ->and($data->winner())->toBe(BaccaratGameWinner::Tiger);

});

test('ensure winner', function () {

    $trader = Trader::factory()->baccaratTrader()->create();

    $data = BaccaratGameSubmitResultData::make(
        user: $trader,
//        dragonTigerGameId: 1,
//        dragonResult: 8,
//        dragonType: BaccaratCard::Heart,
//        tigerResult: 8,
//        tigerType: BaccaratCard::Club

        baccaratGameId: 1,
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

    expect($data->winner())->toBe(BaccaratGameWinner::Tie);

    $data = BaccaratGameSubmitResultData::make(
        user: $trader,
//        dragonTigerGameId: 1,
//        dragonResult: 10,
//        dragonType: BaccaratCard::Heart,
//        tigerResult: 2,
//        tigerType: BaccaratCard::Club

        baccaratGameId: 1,
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

    expect($data->winner())->toBe(BaccaratGameWinner::Player);

});

test('ensure card type and range number are valid', function () {
    $trader = Trader::factory()->baccaratTrader()->create();

    try {
        BaccaratGameSubmitResultData::make(
            user: $trader,
//            dragonTigerGameId: 1,
//            dragonResult: 8,
//            dragonType: 'unknown',
//            tigerResult: 8,
//            tigerType: BaccaratCard::Club

            baccaratGameId: 1,
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
    } catch (Exception $e) {
        expect($e->getMessage())->toBe(BaccaratCardException::invalidType()->getMessage());
    }

    try {
        BaccaratGameSubmitResultData::make(
            user: $trader,
//            dragonTigerGameId: 1,
//            dragonResult: 13,
//            dragonType: BaccaratCard::Heart,
//            tigerResult: 0,
//            tigerType: BaccaratCard::Club

            baccaratGameId: 1,
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
    } catch (Exception $e) {
        expect($e->getMessage())->toBe(BaccaratCardException::invalidRange()->getMessage());
    }

});


