<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameCreateTicketManagerAction;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameMemberBetData;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameBetConditionException;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard;
use App\Kravanh\Domain\Baccarat\tests\BaccaratTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Support\Enums\Currency;
use function Pest\Laravel\seed;

test('Ensure step of create ticket manager, validation, ticket created, and withdraw member balance', function () {

    setupUser(Currency::USD);
    seed(GameSeeder::class);
    $dragonTiger = BaccaratGame::factory()->liveGame()->create();

    //$2500
    $member = BaccaratTestHelper::member(groupId: $dragonTiger->game_table_id);

    //10, 10, 20, 10, 50
    BaccaratTestHelper::setUpConditionForMember($member);

    // ensure validation in place
    try {
        BaccaratGameCreateTicketManagerAction::from(BaccaratGameMemberBetData::make(
            member: $member,
            amount: 10000,
            betOn: BaccaratCard::Tiger,
            betType: BaccaratCard::Tiger,
            ip: '127.0.0.1'
        ));
    } catch (Exception $exception) {
        expect($exception->getMessage())->toBe(BaccaratGameBetConditionException::invalidMaxPerTicket()->getMessage());
    }

    //ensure ticket created and withdraw balance from member successfully
    $balance = BaccaratGameCreateTicketManagerAction::from(BaccaratGameMemberBetData::make(
        member: $member,
        amount: 100,
        betOn: BaccaratCard::Tiger,
        betType: BaccaratCard::Tiger,
        ip: '127.0.0.1'
    ));

    expect(BaccaratTicket::count())->toBe(1)
        ->and($member->balanceInt)->toBe($balance);

});
