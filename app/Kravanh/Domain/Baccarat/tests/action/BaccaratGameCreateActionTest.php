<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameCreateAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameSubmitResultAction;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameCreateData;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameSubmitResultData;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameHasLiveGameException;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard;
use App\Kravanh\Domain\Baccarat\Support\RoundMode;
use App\Kravanh\Domain\User\Models\Trader;
use App\Models\User;
use Illuminate\Support\Carbon;

test('it can create new dragon tiger game', function () {

    $trader = Trader::factory()->dragonTigerTrader()->create();
    $data = BaccaratGameCreateData::from(user: $trader);
    $dragonTigerGame = (new BaccaratGameCreateAction())($data)->refresh();

    expect($dragonTigerGame)->toBeInstanceOf(BaccaratGame::class)
        ->and(BaccaratGame::count())->toBe(1)
        ->and($dragonTigerGame->game_table_id)->toBe($data->gameTableId)
        ->and($dragonTigerGame->user_id)->toBe($data->userId)
        ->and($dragonTigerGame->round)->toBe($data->round)
        ->and($dragonTigerGame->number)->toBe($data->number)
        ->and($dragonTigerGame->closed_bet_at->diffInSeconds($dragonTigerGame->created_at))->toBe(60)
        ->and($dragonTigerGame->isLive())->toBeTrue();

});

test('it can not create new dragon tiger game if has live game', function () {
    $trader = Trader::factory()->dragonTigerTrader()->create();
    $data = BaccaratGameCreateData::from($trader);
    (new BaccaratGameCreateAction())($data)->refresh();

    $data = BaccaratGameCreateData::from($trader);
    (new BaccaratGameCreateAction())($data)->refresh();

})
    ->expectException(BaccaratGameHasLiveGameException::class);

function makeSubmitData(User $trader, int $gameId)
{
    return BaccaratGameSubmitResultData::make(
        user: $trader,
//        dragonTigerGameId: $gameId,
//        dragonResult: 8,
//        dragonType: BaccaratCard::Heart,
//        tigerResult: 6,
//        tigerType: BaccaratCard::Club
        baccaratGameId: $gameId,
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
}

test('ensure round number and hand number are correctly', function () {
    $trader = Trader::factory()->dragonTigerTrader()->create();
    $data1 = BaccaratGameCreateData::from(user: $trader);
    $game1 = (new BaccaratGameCreateAction())($data1)->refresh();

    Carbon::setTestNow(now()->addMinute());
    BaccaratGameSubmitResultAction::from(makeSubmitData($trader, $game1->id));

    expect($game1->round)->toBe(1)
        ->and($game1->number)->toBe(1);

    $data2 = BaccaratGameCreateData::from(user: $trader);
    $game2 = (new BaccaratGameCreateAction())($data2)->refresh();
    expect($game2->round)->toBe(1)
        ->and($game2->number)->toBe(2);

    Carbon::setTestNow(now()->addMinute());
    BaccaratGameSubmitResultAction::from(makeSubmitData($trader, $game2->id));

    $data3 = BaccaratGameCreateData::from(user: $trader, roundMode: RoundMode::NextRound);
    $game3 = (new BaccaratGameCreateAction())($data3)->refresh();
    expect($game3->round)->toBe(2)
        ->and($game3->number)->toBe(1);

    Carbon::setTestNow(now()->addMinute());
    BaccaratGameSubmitResultAction::from(makeSubmitData($trader, $game3->id));

    $data4 = BaccaratGameCreateData::from(user: $trader);
    $game4 = (new BaccaratGameCreateAction())($data4)->refresh();
    expect($game4->round)->toBe(2)
        ->and($game4->number)->toBe(2);

});
