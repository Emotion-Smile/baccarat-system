<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameBetValidatorAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetLiveGameByTableAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameSubmitResultAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameSubmitResultData;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameBetConditionException;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerCard;
use App\Kravanh\Domain\DragonTiger\tests\DragonTigerTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\User\Models\Trader;
use App\Kravanh\Support\Enums\Currency;
use Illuminate\Support\Carbon;

use function Pest\Laravel\seed;

beforeEach(function () {
    setupUser(Currency::USD);
    seed(GameSeeder::class);
    $dragonTiger = DragonTigerGame::factory()->liveGame()->create();

    //$2500
    $member = DragonTigerTestHelper::member(groupId: $dragonTiger->game_table_id);
    //10, 10, 20, 10, 50
    DragonTigerTestHelper::setUpConditionForMember($member);
});

test('ensure the amount bet on dragon or tiger cannot lower than minimum allowed per ticket', fn () => DragonTigerGameBetValidatorAction::validate(
    betData: DragonTigerTestHelper::betTiger(amount: 0)
))->expectExceptionMessage(DragonTigerGameBetConditionException::invalidMinPerTicket()->getMessage());

test('ensure the amount bet on dragon or tiger cannot greater than maximum allowed per ticket', fn () => DragonTigerGameBetValidatorAction::validate(
    betData: DragonTigerTestHelper::betTiger(amount: 501) // max = 500
))->expectExceptionMessage(DragonTigerGameBetConditionException::invalidMaxPerTicket()->getMessage());

test('ensure the amount bet on red black cannot lower than minimum allowed per ticket', fn () => DragonTigerGameBetValidatorAction::validate(
    betData: DragonTigerTestHelper::betDragonRed(amount: 0) // mix = 1
))->expectExceptionMessage(DragonTigerGameBetConditionException::invalidMinPerTicket()->getMessage());

test('ensure the amount bet on red black cannot greater than maximum allowed per ticket', fn () => DragonTigerGameBetValidatorAction::validate(
    betData: DragonTigerTestHelper::betDragonRed(amount: 501) // mix = 1
))->expectExceptionMessage(DragonTigerGameBetConditionException::invalidMaxPerTicket()->getMessage());

test('ensure the total bet amount in single game cannot greater than maximum allowed per game', function () {

    $member = DragonTigerTestHelper::member();
    $game = DragonTigerGame::query()->whereLiveGame()->first();

    DragonTigerTicket::factory([
        'game_table_id' => $member->getGameTableId(),
        'dragon_tiger_game_id' => $game->id,
        'user_id' => $member->id,
        'amount' => 3_800_000,
    ])->create();

    //game limit 1000$
    DragonTigerGameBetValidatorAction::validate(
        betData: DragonTigerTestHelper::betDragonRed(amount: 51) // mix = 1
    );

})->expectExceptionMessage(DragonTigerGameBetConditionException::invalidMaxPerTicket()->getMessage());

test('ensure the member win limit per day cannot greater than condition', function () {

    $member = DragonTigerTestHelper::member();
    $game = DragonTigerGame::factory()->create();

    DragonTigerTicket::factory([
        'game_table_id' => $member->getGameTableId(),
        'bet_on' => DragonTigerCard::Tiger,
        'bet_type' => DragonTigerCard::Tiger,
        'dragon_tiger_game_id' => $game->id,
        'user_id' => $member->id,
        'amount' => 8_000_100,
    ])->create();

    $submitData = DragonTigerGameSubmitResultData::make(
        user: Trader::factory()->dragonTigerTrader()->create(),
        dragonTigerGameId: $game->id,
        dragonResult: 8,
        dragonType: DragonTigerCard::Heart,
        tigerResult: 11,
        tigerType: DragonTigerCard::Club
    );

    Carbon::setTestNow(now()->addMinute());
    $isOk = (new DragonTigerGameSubmitResultAction())($submitData);

    expect($isOk)->toBeTrue();

    //win limit per day 2000$
    $gameLive = (new DragonTigerGameGetLiveGameByTableAction())($member->getGameTableId());
    $gameLive->closed_bet_at = now()->addHours();
    $gameLive->saveQuietly();

    DragonTigerGameBetValidatorAction::validate(
        betData: DragonTigerTestHelper::betDragonRed(amount: 51) // mix = 1
    );

})->expectExceptionMessage(DragonTigerGameBetConditionException::overWinLimitPerDay()->getMessage());
