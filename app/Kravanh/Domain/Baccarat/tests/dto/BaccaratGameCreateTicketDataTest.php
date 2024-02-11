<?php

use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameCreateTicketData;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameMemberBetData;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard;
use App\Kravanh\Domain\Baccarat\tests\BaccaratTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\Enums\Currency;
use function Pest\Laravel\seed;


test('it can create dragon tiger game ticket data', function () {

    $game = BaccaratGame::factory()->liveGame()->create();

    $member = Member::factory([
        'group_id' => $game->game_table_id
    ])->create();

    $memberBetData = BaccaratGameMemberBetData::make(
        member: $member,
        amount: 0,
        betOn: BaccaratCard::Tiger,
        betType: BaccaratCard::Tiger,
        ip: '127.0.0.1'
    );

    $ticketData = BaccaratGameCreateTicketData::make($memberBetData);

    expect($ticketData)->toBeInstanceOf(BaccaratGameCreateTicketData::class)
        ->and($ticketData->member)->toBe($member)
        ->and($ticketData->payoutRate)->toBe(1.0)
        ->and($ticketData->gameTableId)->toBe($game->game_table_id)
        ->and($ticketData->dragonTigerGameId)->toBe($game->id)
        ->and($ticketData->betOn)->toBe(BaccaratCard::Tiger)
        ->and($ticketData->betType)->toBe(BaccaratCard::Tiger)
        ->and($ticketData->ip)->toBe('127.0.0.1');


    $memberBetData->betOn = BaccaratCard::Tie;
    $memberBetData->betType = BaccaratCard::Tie;

    expect(BaccaratGameCreateTicketData::make($memberBetData)->payoutRate)->toBe(7.0);

});

test('it can build share and commission', closure: function () {

    setupUser(Currency::USD);
    seed(GameSeeder::class);
    $dragonTiger = BaccaratGame::factory()->liveGame()->create();

    //$2500
    $member = Member::whereName('member_1')->whereType('member')->first();
    $member->group_id = $dragonTiger->game_table_id;
    $member->saveQuietly();

    //10, 10, 20, 10, 50
    BaccaratTestHelper::setUpConditionForMember($member);

    $memberBetData = BaccaratGameMemberBetData::make(
        member: $member,
        amount: 0,
        betOn: BaccaratCard::Tiger,
        betType: BaccaratCard::Tiger,
        ip: '127.0.0.1'
    );

    $ticketData = BaccaratGameCreateTicketData::make($memberBetData);

    expect($ticketData->member)->toBe($member)
        ->and(count($ticketData->share()))->toBe(6)
        ->and(count($ticketData->commission()))->toBe(6);
});

