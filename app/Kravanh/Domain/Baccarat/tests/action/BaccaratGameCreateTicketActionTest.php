<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameCreateTicketAction;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameCreateTicketData;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameMemberBetData;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard;
use App\Kravanh\Domain\Baccarat\Support\TicketStatus;
use App\Kravanh\Domain\Baccarat\tests\BaccaratTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\Enums\Currency;

use function Pest\Laravel\seed;

test('it can create ticket', function () {

    setupUser(Currency::USD);
    seed(GameSeeder::class);
    $dragonTiger = BaccaratGame::factory()->liveGame()->create();

    //$2500
    $member = Member::whereName('member_1')->whereType('member')->first();
    $member->group_id = $dragonTiger->game_table_id;
    $member->saveQuietly();

    //10, 10, 20, 10, 50
    BaccaratTestHelper::setUpConditionForMember($member);

    $data = BaccaratGameCreateTicketData::make(
        BaccaratGameMemberBetData::make(
            member: $member,
            amount: 10,
            betOn: BaccaratCard::Tiger,
            betType: BaccaratCard::Tiger,
            ip: '127.0.0.1'
        )
    );

    $ticket = BaccaratGameCreateTicketAction::from($data);
    $ticket->refresh();

    expect($ticket)->toBeInstanceOf(BaccaratTicket::class)
        ->and(BaccaratTicket::count())->toBe(1)
        ->and($ticket->game_table_id)->toBe($data->gameTableId)
        ->and($ticket->dragon_tiger_game_id)->toBe($data->dragonTigerGameId)
        ->and($ticket->user_id)->toBe($data->member->id)
        ->and($ticket->agent)->toBe($data->member->agent)
        ->and($ticket->master_agent)->toBe($data->member->master_agent)
        ->and($ticket->senior)->toBe($data->member->senior)
        ->and($ticket->super_senior)->toBe($data->member->super_senior)
        ->and($ticket->amount)->toBe(40000) //10 * 4000
        ->and($ticket->bet_on)->toBe($data->betOn)
        ->and($ticket->bet_type)->toBe($data->betType)
        ->and($ticket->status)->toBe(TicketStatus::Accepted)
        ->and($ticket->payout_rate)->toBe(1.0)
        ->and(count($ticket->share))->toBe(count($data->share()))
        ->and(count($ticket->commission))->toBe(count($data->commission()))
        ->and($ticket->ip)->toBe($data->ip)
        ->and($ticket->in_year)->toBe(now()->year)
        ->and($ticket->in_month)->toBe(now()->month)
        ->and($ticket->in_day)->toBe((int) now()->format('Ymd'));

});

test('ensure payout rate are correct', function () {

    setupUser(Currency::USD);
    seed(GameSeeder::class);
    $dragonTiger = BaccaratGame::factory()->liveGame()->create();

    //$2500
    $member = Member::whereName('member_1')->whereType('member')->first();
    $member->group_id = $dragonTiger->game_table_id;
    $member->saveQuietly();

    //10, 10, 20, 10, 50
    BaccaratTestHelper::setUpConditionForMember($member);

    $createTicket = function ($betOn, $betType, $expectedPayoutRate, $payout) use ($member) {
        $data = BaccaratGameCreateTicketData::make(
            BaccaratGameMemberBetData::make(
                member: $member,
                amount: 10,
                betOn: $betOn,
                betType: $betType,
                ip: '127.0.0.1'
            )
        );

        $ticket = BaccaratGameCreateTicketAction::from($data);
        $ticket->refresh();
        expect($ticket->payout_rate)->toBe($expectedPayoutRate)
            ->and($ticket->payout)->toBe($payout);
    };

    $createTicket(BaccaratCard::Tiger, BaccaratCard::Tiger, 1.0, 40000);
    $createTicket(BaccaratCard::Tiger, BaccaratCard::Black, 0.90, 36000);
    $createTicket(BaccaratCard::Tiger, BaccaratCard::Red, 0.90, 36000);
    $createTicket(BaccaratCard::Tie, BaccaratCard::Tie, 7.0, 280000);
});
