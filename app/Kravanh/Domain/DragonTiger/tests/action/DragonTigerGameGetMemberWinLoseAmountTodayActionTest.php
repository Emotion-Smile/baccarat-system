<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameCreateTicketAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetMemberWinLoseAmountTodayAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameSubmitResultAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameCreateTicketData;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameMemberBetData;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameSubmitResultData;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerCard as Card;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerGameWinner;
use App\Kravanh\Domain\DragonTiger\tests\DragonTigerTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Models\Trader;
use App\Kravanh\Support\Enums\Currency;
use Illuminate\Support\Carbon;

use function Pest\Laravel\seed;

test(/**
 * @throws \App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameSubmitResultBetOpenException
 * @throws \App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameBetOnInvalidException
 * @throws \App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameNoLiveGameException
 * @throws Throwable
 */ 'it can get member win lose today', function () {

    setupUser(Currency::KHR);
    seed(GameSeeder::class);
    $dragonTiger = DragonTigerGame::factory()->liveGame()->create();

    //$2500
    $member = Member::whereName('member_1')->whereType('member')->first();
    $member->group_id = $dragonTiger->game_table_id;
    $member->saveQuietly();

    //10, 10, 20, 10, 50
    DragonTigerTestHelper::setUpConditionForMember($member);

    //ticket bet on:
    //1. tiger
    //2. tiger_red
    //3. tie
    //4. dragon_black
    $memberBetData = DragonTigerGameMemberBetData::make(
        member: $member,
        amount: 4_000,
        betOn: Card::Tiger,
        betType: Card::Tiger,
        ip: '127.0.0.1'
    );

    DragonTigerGameCreateTicketAction::from(
        DragonTigerGameCreateTicketData::make($memberBetData)
    );

    $memberBetData->betOn = Card::Tiger;
    $memberBetData->betType = Card::Red;
    DragonTigerGameCreateTicketAction::from(
        DragonTigerGameCreateTicketData::make($memberBetData)
    );

    $memberBetData->betOn = Card::Tie;
    $memberBetData->betType = Card::Tie;
    DragonTigerGameCreateTicketAction::from(
        DragonTigerGameCreateTicketData::make($memberBetData)
    );

    $memberBetData->betOn = Card::Dragon;
    $memberBetData->betType = Card::Black;
    DragonTigerGameCreateTicketAction::from(
        DragonTigerGameCreateTicketData::make($memberBetData)
    );

    $trader = Trader::factory()->dragonTigerTrader()->create();

    //With this result all tickets are lose
    $submitData = DragonTigerGameSubmitResultData::make(
        user: $trader,
        dragonTigerGameId: $dragonTiger->id,
        dragonResult: 8,
        dragonType: Card::Heart,
        tigerResult: 6,
        tigerType: Card::Club
    );

    Carbon::setTestNow(now()->addMinute());
    (new DragonTigerGameSubmitResultAction())($submitData);
    $dragonTiger->refresh();

    expect($dragonTiger->isLive())->toBeFalse()
        ->and($dragonTiger->winner)->toBe(DragonTigerGameWinner::Dragon)
        ->and(DragonTigerTicket::where('user_id', $member->id)->count())->toBe(4)
        ->and((int) DragonTigerTicket::where('user_id', $member->id)->sum('amount'))->toBe(16000);

    $winLose = (new DragonTigerGameGetMemberWinLoseAmountTodayAction())($member->id);
    expect($winLose)->toBe(-16000);

    $submitData = DragonTigerGameSubmitResultData::make(
        user: $trader,
        dragonTigerGameId: $dragonTiger->id,
        dragonResult: 8,
        dragonType: Card::Heart,
        tigerResult: 8,
        tigerType: Card::Spade
    );

    (new DragonTigerGameSubmitResultAction())($submitData);
    //ticket tie: win = 4_000 * 7 = 2_8000
    //one ticket bet on tiger lose half
    $winLose = (new DragonTigerGameGetMemberWinLoseAmountTodayAction())($member->id);
    expect($winLose)->toBe(1_8000);

    $submitData = DragonTigerGameSubmitResultData::make(
        user: $trader,
        dragonTigerGameId: $dragonTiger->id,
        dragonResult: 1,
        dragonType: Card::Heart,
        tigerResult: 8,
        tigerType: Card::Spade
    );

    (new DragonTigerGameSubmitResultAction())($submitData);
    // ticket: tiger win
    $winLose = (new DragonTigerGameGetMemberWinLoseAmountTodayAction())($member->id);
    expect($winLose)->toBe(-8000);

    $submitData = DragonTigerGameSubmitResultData::make(
        user: $trader,
        dragonTigerGameId: $dragonTiger->id,
        dragonResult: 1,
        dragonType: Card::Club,
        tigerResult: 8,
        tigerType: Card::Spade
    );

    (new DragonTigerGameSubmitResultAction())($submitData);
    // ticket: tiger and dragon_black win,
    $winLose = (new DragonTigerGameGetMemberWinLoseAmountTodayAction())($member->id);
    expect($winLose)->toBe(-400);

    $submitData = DragonTigerGameSubmitResultData::make(
        user: $trader,
        dragonTigerGameId: $dragonTiger->id,
        dragonResult: 1,
        dragonType: Card::Diamond,
        tigerResult: 8,
        tigerType: Card::Diamond
    );

    (new DragonTigerGameSubmitResultAction())($submitData);
    // ticket: tiger and tiger_red win,
    $winLose = (new DragonTigerGameGetMemberWinLoseAmountTodayAction())($member->id);
    expect($winLose)->toBe(-400);

});
