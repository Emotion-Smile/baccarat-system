<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameCreateTicketAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetMemberWinLoseAmountTodayAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameSubmitResultAction;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameCreateTicketData;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameMemberBetData;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameSubmitResultData;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard as Card;
use App\Kravanh\Domain\Baccarat\Support\BaccaratGameWinner;
use App\Kravanh\Domain\Baccarat\tests\BaccaratTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Models\Trader;
use App\Kravanh\Support\Enums\Currency;
use Illuminate\Support\Carbon;

use function Pest\Laravel\seed;

test(/**
 * @throws \App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameSubmitResultBetOpenException
 * @throws \App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameBetOnInvalidException
 * @throws \App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameNoLiveGameException
 * @throws Throwable
 */ 'it can get member win lose today', function () {

    setupUser(Currency::KHR);
    seed(GameSeeder::class);
    $dragonTiger = BaccaratGame::factory()->liveGame()->create();

    //$2500
    $member = Member::whereName('member_1')->whereType('member')->first();
    $member->group_id = $dragonTiger->game_table_id;
    $member->saveQuietly();

    //10, 10, 20, 10, 50
    BaccaratTestHelper::setUpConditionForMember($member);

    //ticket bet on:
    //1. tiger
    //2. tiger_red
    //3. tie
    //4. dragon_black
    $memberBetData = BaccaratGameMemberBetData::make(
        member: $member,
        amount: 4_000,
        betOn: Card::Banker,
        betType: Card::Banker,
        ip: '127.0.0.1'
    );

    BaccaratGameCreateTicketAction::from(
        BaccaratGameCreateTicketData::make($memberBetData)
    );

    $memberBetData->betOn = Card::Banker;
    $memberBetData->betType = Card::Red;
    BaccaratGameCreateTicketAction::from(
        BaccaratGameCreateTicketData::make($memberBetData)
    );

    $memberBetData->betOn = Card::Tie;
    $memberBetData->betType = Card::Tie;
    BaccaratGameCreateTicketAction::from(
        BaccaratGameCreateTicketData::make($memberBetData)
    );

    $memberBetData->betOn = Card::Player;
    $memberBetData->betType = Card::Black;
    BaccaratGameCreateTicketAction::from(
        BaccaratGameCreateTicketData::make($memberBetData)
    );

    $trader = Trader::factory()->dragonTigerTrader()->create();

    //With this result all tickets are lose
    $submitData = BaccaratGameSubmitResultData::make(
        user: $trader,
//        dragonTigerGameId: $dragonTiger->id,
//        dragonResult: 8,
//        dragonType: Card::Heart,
//        tigerResult: 6,
//        tigerType: Card::Club
        baccaratGameId: $dragonTiger->id,
        playerFirstCardValue: 4,
        playerFirstCardType: Card::Heart,
        playerSecondCardValue: 4,
        playerSecondCardType: Card::Spade,
        playerThirdCardValue: 1,
        playerThirdCardType: Card::Club,
        playerPoints: 9,
        bankerFirstCardValue: 2,
        bankerFirstCardType: Card::Diamond,
        bankerSecondCardValue: 4,
        bankerSecondCardType: Card::Heart,
        bankerThirdCardValue: 2,
        bankerThirdCardType: Card::Club,
        bankerPoints: 8
    );

    Carbon::setTestNow(now()->addMinute());
    (new BaccaratGameSubmitResultAction())($submitData);
    $dragonTiger->refresh();

    expect($dragonTiger->isLive())->toBeFalse()
        ->and($dragonTiger->winner)->toBe(BaccaratGameWinner::Dragon)
        ->and(BaccaratTicket::where('user_id', $member->id)->count())->toBe(4)
        ->and((int) BaccaratTicket::where('user_id', $member->id)->sum('amount'))->toBe(16000);

    $winLose = (new BaccaratGameGetMemberWinLoseAmountTodayAction())($member->id);
    expect($winLose)->toBe(-16000);

    $submitData = BaccaratGameSubmitResultData::make(
        user: $trader,
//        dragonTigerGameId: $dragonTiger->id,
//        dragonResult: 8,
//        dragonType: Card::Heart,
//        tigerResult: 8,
//        tigerType: Card::Spade
        baccaratGameId: $dragonTiger->id,
        playerFirstCardValue: 4,
        playerFirstCardType: Card::Heart,
        playerSecondCardValue: 4,
        playerSecondCardType: Card::Spade,
        playerThirdCardValue: 1,
        playerThirdCardType: Card::Club,
        playerPoints: 9,
        bankerFirstCardValue: 2,
        bankerFirstCardType: Card::Diamond,
        bankerSecondCardValue: 4,
        bankerSecondCardType: Card::Heart,
        bankerThirdCardValue: 2,
        bankerThirdCardType: Card::Club,
        bankerPoints: 8
    );

    (new BaccaratGameSubmitResultAction())($submitData);
    //ticket tie: win = 4_000 * 7 = 2_8000
    //one ticket bet on tiger lose half
    $winLose = (new BaccaratGameGetMemberWinLoseAmountTodayAction())($member->id);
    expect($winLose)->toBe(1_8000);

    $submitData = BaccaratGameSubmitResultData::make(
        user: $trader,
//        dragonTigerGameId: $dragonTiger->id,
//        dragonResult: 1,
//        dragonType: Card::Heart,
//        tigerResult: 8,
//        tigerType: Card::Spade
        baccaratGameId: $dragonTiger->id,
        playerFirstCardValue: 4,
        playerFirstCardType: Card::Heart,
        playerSecondCardValue: 4,
        playerSecondCardType: Card::Spade,
        playerThirdCardValue: 1,
        playerThirdCardType: Card::Club,
        playerPoints: 9,
        bankerFirstCardValue: 2,
        bankerFirstCardType: Card::Diamond,
        bankerSecondCardValue: 4,
        bankerSecondCardType: Card::Heart,
        bankerThirdCardValue: 2,
        bankerThirdCardType: Card::Club,
        bankerPoints: 8
    );

    (new BaccaratGameSubmitResultAction())($submitData);
    // ticket: tiger win
    $winLose = (new BaccaratGameGetMemberWinLoseAmountTodayAction())($member->id);
    expect($winLose)->toBe(-8000);

    $submitData = BaccaratGameSubmitResultData::make(
        user: $trader,
//        dragonTigerGameId: $dragonTiger->id,
//        dragonResult: 1,
//        dragonType: Card::Club,
//        tigerResult: 8,
//        tigerType: Card::Spade
        baccaratGameId: $dragonTiger->id,
        playerFirstCardValue: 4,
        playerFirstCardType: Card::Heart,
        playerSecondCardValue: 4,
        playerSecondCardType: Card::Spade,
        playerThirdCardValue: 1,
        playerThirdCardType: Card::Club,
        playerPoints: 9,
        bankerFirstCardValue: 2,
        bankerFirstCardType: Card::Diamond,
        bankerSecondCardValue: 4,
        bankerSecondCardType: Card::Heart,
        bankerThirdCardValue: 2,
        bankerThirdCardType: Card::Club,
        bankerPoints: 8
    );

    (new BaccaratGameSubmitResultAction())($submitData);
    // ticket: tiger and dragon_black win,
    $winLose = (new BaccaratGameGetMemberWinLoseAmountTodayAction())($member->id);
    expect($winLose)->toBe(-400);

    $submitData = BaccaratGameSubmitResultData::make(
        user: $trader,
//        dragonTigerGameId: $dragonTiger->id,
//        dragonResult: 1,
//        dragonType: Card::Diamond,
//        tigerResult: 8,
//        tigerType: Card::Diamond
        baccaratGameId: $dragonTiger->id,
        playerFirstCardValue: 4,
        playerFirstCardType: Card::Heart,
        playerSecondCardValue: 4,
        playerSecondCardType: Card::Spade,
        playerThirdCardValue: 1,
        playerThirdCardType: Card::Club,
        playerPoints: 9,
        bankerFirstCardValue: 2,
        bankerFirstCardType: Card::Diamond,
        bankerSecondCardValue: 4,
        bankerSecondCardType: Card::Heart,
        bankerThirdCardValue: 2,
        bankerThirdCardType: Card::Club,
        bankerPoints: 8
    );

    (new BaccaratGameSubmitResultAction())($submitData);
    // ticket: tiger and tiger_red win,
    $winLose = (new BaccaratGameGetMemberWinLoseAmountTodayAction())($member->id);
    expect($winLose)->toBe(-400);

});
