<?php

use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameCreateTicketData;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameMemberBetData;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerCard;
use App\Kravanh\Domain\DragonTiger\tests\DragonTigerTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\Enums\Currency;
use function Pest\Laravel\seed;


test('it can create dragon tiger game ticket data', function () {

    $game = DragonTigerGame::factory()->liveGame()->create();

    $member = Member::factory([
        'group_id' => $game->game_table_id
    ])->create();

    $memberBetData = DragonTigerGameMemberBetData::make(
        member: $member,
        amount: 0,
        betOn: DragonTigerCard::Tiger,
        betType: DragonTigerCard::Tiger,
        ip: '127.0.0.1'
    );

    $ticketData = DragonTigerGameCreateTicketData::make($memberBetData);

    expect($ticketData)->toBeInstanceOf(DragonTigerGameCreateTicketData::class)
        ->and($ticketData->member)->toBe($member)
        ->and($ticketData->payoutRate)->toBe(1.0)
        ->and($ticketData->gameTableId)->toBe($game->game_table_id)
        ->and($ticketData->dragonTigerGameId)->toBe($game->id)
        ->and($ticketData->betOn)->toBe(DragonTigerCard::Tiger)
        ->and($ticketData->betType)->toBe(DragonTigerCard::Tiger)
        ->and($ticketData->ip)->toBe('127.0.0.1');


    $memberBetData->betOn = DragonTigerCard::Tie;
    $memberBetData->betType = DragonTigerCard::Tie;

    expect(DragonTigerGameCreateTicketData::make($memberBetData)->payoutRate)->toBe(7.0);

});

test('it can build share and commission', closure: function () {

    setupUser(Currency::USD);
    seed(GameSeeder::class);
    $dragonTiger = DragonTigerGame::factory()->liveGame()->create();

    //$2500
    $member = Member::whereName('member_1')->whereType('member')->first();
    $member->group_id = $dragonTiger->game_table_id;
    $member->saveQuietly();

    //10, 10, 20, 10, 50
    DragonTigerTestHelper::setUpConditionForMember($member);

    $memberBetData = DragonTigerGameMemberBetData::make(
        member: $member,
        amount: 0,
        betOn: DragonTigerCard::Tiger,
        betType: DragonTigerCard::Tiger,
        ip: '127.0.0.1'
    );

    $ticketData = DragonTigerGameCreateTicketData::make($memberBetData);

    expect($ticketData->member)->toBe($member)
        ->and(count($ticketData->share()))->toBe(6)
        ->and(count($ticketData->commission()))->toBe(6);
});

