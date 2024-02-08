<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameCreateTicketManagerAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameMemberBetData;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameBetConditionException;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerCard;
use App\Kravanh\Domain\DragonTiger\tests\DragonTigerTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Support\Enums\Currency;
use function Pest\Laravel\seed;

test('Ensure step of create ticket manager, validation, ticket created, and withdraw member balance', function () {

    setupUser(Currency::USD);
    seed(GameSeeder::class);
    $dragonTiger = DragonTigerGame::factory()->liveGame()->create();

    //$2500
    $member = DragonTigerTestHelper::member(groupId: $dragonTiger->game_table_id);

    //10, 10, 20, 10, 50
    DragonTigerTestHelper::setUpConditionForMember($member);

    // ensure validation in place
    try {
        DragonTigerGameCreateTicketManagerAction::from(DragonTigerGameMemberBetData::make(
            member: $member,
            amount: 10000,
            betOn: DragonTigerCard::Tiger,
            betType: DragonTigerCard::Tiger,
            ip: '127.0.0.1'
        ));
    } catch (Exception $exception) {
        expect($exception->getMessage())->toBe(DragonTigerGameBetConditionException::invalidMaxPerTicket()->getMessage());
    }

    //ensure ticket created and withdraw balance from member successfully
    $balance = DragonTigerGameCreateTicketManagerAction::from(DragonTigerGameMemberBetData::make(
        member: $member,
        amount: 100,
        betOn: DragonTigerCard::Tiger,
        betType: DragonTigerCard::Tiger,
        ip: '127.0.0.1'
    ));

    expect(DragonTigerTicket::count())->toBe(1)
        ->and($member->balanceInt)->toBe($balance);

});
