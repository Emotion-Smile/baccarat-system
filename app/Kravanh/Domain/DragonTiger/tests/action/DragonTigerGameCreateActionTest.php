<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameCreateAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameSubmitResultAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameCreateData;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameSubmitResultData;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameHasLiveGameException;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerCard;
use App\Kravanh\Domain\DragonTiger\Support\RoundMode;
use App\Kravanh\Domain\User\Models\Trader;
use App\Models\User;
use Illuminate\Support\Carbon;

test('it can create new dragon tiger game', function () {

    $trader = Trader::factory()->dragonTigerTrader()->create();
    $data = DragonTigerGameCreateData::from(user: $trader);
    $dragonTigerGame = (new DragonTigerGameCreateAction())($data)->refresh();

    expect($dragonTigerGame)->toBeInstanceOf(DragonTigerGame::class)
        ->and(DragonTigerGame::count())->toBe(1)
        ->and($dragonTigerGame->game_table_id)->toBe($data->gameTableId)
        ->and($dragonTigerGame->user_id)->toBe($data->userId)
        ->and($dragonTigerGame->round)->toBe($data->round)
        ->and($dragonTigerGame->number)->toBe($data->number)
        ->and($dragonTigerGame->closed_bet_at->diffInSeconds($dragonTigerGame->created_at))->toBe(60)
        ->and($dragonTigerGame->isLive())->toBeTrue();

});

test('it can not create new dragon tiger game if has live game', function () {
    $trader = Trader::factory()->dragonTigerTrader()->create();
    $data = DragonTigerGameCreateData::from($trader);
    (new DragonTigerGameCreateAction())($data)->refresh();

    $data = DragonTigerGameCreateData::from($trader);
    (new DragonTigerGameCreateAction())($data)->refresh();

})
    ->expectException(DragonTigerGameHasLiveGameException::class);

function makeSubmitData(User $trader, int $gameId)
{
    return DragonTigerGameSubmitResultData::make(
        user: $trader,
        dragonTigerGameId: $gameId,
        dragonResult: 8,
        dragonType: DragonTigerCard::Heart,
        tigerResult: 6,
        tigerType: DragonTigerCard::Club
    );
}

test('ensure round number and hand number are correctly', function () {
    $trader = Trader::factory()->dragonTigerTrader()->create();
    $data1 = DragonTigerGameCreateData::from(user: $trader);
    $game1 = (new DragonTigerGameCreateAction())($data1)->refresh();

    Carbon::setTestNow(now()->addMinute());
    DragonTigerGameSubmitResultAction::from(makeSubmitData($trader, $game1->id));

    expect($game1->round)->toBe(1)
        ->and($game1->number)->toBe(1);

    $data2 = DragonTigerGameCreateData::from(user: $trader);
    $game2 = (new DragonTigerGameCreateAction())($data2)->refresh();
    expect($game2->round)->toBe(1)
        ->and($game2->number)->toBe(2);

    Carbon::setTestNow(now()->addMinute());
    DragonTigerGameSubmitResultAction::from(makeSubmitData($trader, $game2->id));

    $data3 = DragonTigerGameCreateData::from(user: $trader, roundMode: RoundMode::NextRound);
    $game3 = (new DragonTigerGameCreateAction())($data3)->refresh();
    expect($game3->round)->toBe(2)
        ->and($game3->number)->toBe(1);

    Carbon::setTestNow(now()->addMinute());
    DragonTigerGameSubmitResultAction::from(makeSubmitData($trader, $game3->id));

    $data4 = DragonTigerGameCreateData::from(user: $trader);
    $game4 = (new DragonTigerGameCreateAction())($data4)->refresh();
    expect($game4->round)->toBe(2)
        ->and($game4->number)->toBe(2);

});
