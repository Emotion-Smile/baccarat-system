<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameBetValidatorAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetLiveGameByTableAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameSubmitResultAction;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameSubmitResultData;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameBetConditionException;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard;
use App\Kravanh\Domain\Baccarat\tests\BaccaratTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\User\Models\Trader;
use App\Kravanh\Support\Enums\Currency;
use Illuminate\Support\Carbon;

use function Pest\Laravel\seed;

beforeEach(function () {
    setupUser(Currency::USD);
    seed(GameSeeder::class);
    $dragonTiger = BaccaratGame::factory()->liveGame()->create();

    //$2500
    $member = BaccaratTestHelper::member(groupId: $dragonTiger->game_table_id);
    //10, 10, 20, 10, 50
    BaccaratTestHelper::setUpConditionForMember($member);
});

test('ensure the amount bet on dragon or tiger cannot lower than minimum allowed per ticket', fn () => BaccaratGameBetValidatorAction::validate(
    betData: BaccaratTestHelper::betTiger(amount: 0)
))->expectExceptionMessage(BaccaratGameBetConditionException::invalidMinPerTicket()->getMessage());

test('ensure the amount bet on dragon or tiger cannot greater than maximum allowed per ticket', fn () => BaccaratGameBetValidatorAction::validate(
    betData: BaccaratTestHelper::betTiger(amount: 501) // max = 500
))->expectExceptionMessage(BaccaratGameBetConditionException::invalidMaxPerTicket()->getMessage());

test('ensure the amount bet on red black cannot lower than minimum allowed per ticket', fn () => BaccaratGameBetValidatorAction::validate(
    betData: BaccaratTestHelper::betDragonRed(amount: 0) // mix = 1
))->expectExceptionMessage(BaccaratGameBetConditionException::invalidMinPerTicket()->getMessage());

test('ensure the amount bet on red black cannot greater than maximum allowed per ticket', fn () => BaccaratGameBetValidatorAction::validate(
    betData: BaccaratTestHelper::betDragonRed(amount: 501) // mix = 1
))->expectExceptionMessage(BaccaratGameBetConditionException::invalidMaxPerTicket()->getMessage());

test('ensure the total bet amount in single game cannot greater than maximum allowed per game', function () {

    $member = BaccaratTestHelper::member();
    $game = BaccaratGame::query()->whereLiveGame()->first();

    BaccaratTicket::factory([
        'game_table_id' => $member->getGameTableId(),
        'dragon_tiger_game_id' => $game->id,
        'user_id' => $member->id,
        'amount' => 3_800_000,
    ])->create();

    //game limit 1000$
    BaccaratGameBetValidatorAction::validate(
        betData: BaccaratTestHelper::betDragonRed(amount: 51) // mix = 1
    );

})->expectExceptionMessage(BaccaratGameBetConditionException::invalidMaxPerTicket()->getMessage());

test('ensure the member win limit per day cannot greater than condition', function () {

    $member = BaccaratTestHelper::member();
    $game = BaccaratGame::factory()->create();

    BaccaratTicket::factory([
        'game_table_id' => $member->getGameTableId(),
        'bet_on' => BaccaratCard::Banker,
        'bet_type' => BaccaratCard::Banker,
        'dragon_tiger_game_id' => $game->id,
        'user_id' => $member->id,
        'amount' => 8_000_100,
    ])->create();

    $submitData = BaccaratGameSubmitResultData::make(
        user: Trader::factory()->dragonTigerTrader()->create(),
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
    $isOk = (new BaccaratGameSubmitResultAction())($submitData);

    expect($isOk)->toBeTrue();

    //win limit per day 2000$
    $gameLive = (new BaccaratGameGetLiveGameByTableAction())($member->getGameTableId());
    $gameLive->closed_bet_at = now()->addHours();
    $gameLive->saveQuietly();

    BaccaratGameBetValidatorAction::validate(
        betData: BaccaratTestHelper::betDragonRed(amount: 51) // mix = 1
    );

})->expectExceptionMessage(BaccaratGameBetConditionException::overWinLimitPerDay()->getMessage());
