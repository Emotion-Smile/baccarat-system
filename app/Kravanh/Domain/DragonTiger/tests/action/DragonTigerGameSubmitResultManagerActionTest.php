<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameSubmitResultManagerAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameSubmitResultData;
use App\Kravanh\Domain\DragonTiger\Events\DragonTigerGameResultSubmitted;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerCard;
use App\Kravanh\Domain\User\Models\Trader;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;

test('successful result submission for dragon tiger game', function () {

    Event::fake();

    $game = DragonTigerGame::factory()->liveGame()->create();

    $data = DragonTigerGameSubmitResultData::make(
        user: Trader::factory()->dragonTigerTrader()->create(),
        dragonTigerGameId: $game->id,
        dragonResult: 8,
        dragonType: DragonTigerCard::Heart,
        tigerResult: 11,
        tigerType: DragonTigerCard::Club
    );

    Carbon::setTestNow(now()->addMinute());

    (new DragonTigerGameSubmitResultManagerAction())(
        data: $data
    );

    expect($game->refresh()->isLive())->toBeFalse();

    Event::assertDispatched(DragonTigerGameResultSubmitted::class);

});
