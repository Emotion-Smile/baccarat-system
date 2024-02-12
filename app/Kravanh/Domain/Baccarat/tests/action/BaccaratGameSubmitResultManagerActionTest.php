<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameSubmitResultManagerAction;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameSubmitResultData;
use App\Kravanh\Domain\Baccarat\Events\BaccaratGameResultSubmitted;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard;
use App\Kravanh\Domain\User\Models\Trader;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;

test('successful result submission for dragon tiger game', function () {

    Event::fake();

    $game = BaccaratGame::factory()->liveGame()->create();

    $data = BaccaratGameSubmitResultData::make(
        user: Trader::factory()->baccaratTrader()->create(),
//        dragonTigerGameId: $game->id,
//        dragonResult: 8,
//        dragonType: BaccaratCard::Heart,
//        tigerResult: 11,
//        tigerType: BaccaratCard::Club
        baccaratGameId: $game->id,
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

    (new BaccaratGameSubmitResultManagerAction())(
        data: $data
    );

    expect($game->refresh()->isLive())->toBeFalse();

    Event::assertDispatched(BaccaratGameResultSubmitted::class);

});
