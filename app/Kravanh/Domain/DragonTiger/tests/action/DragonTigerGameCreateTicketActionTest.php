<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameCreateTicketAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameCreateTicketData;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameMemberBetData;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerCard;
use App\Kravanh\Domain\DragonTiger\Support\TicketStatus;
use App\Kravanh\Domain\DragonTiger\tests\DragonTigerTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\Enums\Currency;

use function Pest\Laravel\seed;

test('it can create ticket', function () {

    setupUser(Currency::USD);
    seed(GameSeeder::class);
    $dragonTiger = DragonTigerGame::factory()->liveGame()->create();

    //$2500
    $member = Member::whereName('member_1')->whereType('member')->first();
    $member->group_id = $dragonTiger->game_table_id;
    $member->saveQuietly();

    //10, 10, 20, 10, 50
    DragonTigerTestHelper::setUpConditionForMember($member);

    $data = DragonTigerGameCreateTicketData::make(
        DragonTigerGameMemberBetData::make(
            member: $member,
            amount: 10,
            betOn: DragonTigerCard::Tiger,
            betType: DragonTigerCard::Tiger,
            ip: '127.0.0.1'
        )
    );

    $ticket = DragonTigerGameCreateTicketAction::from($data);
    $ticket->refresh();

    expect($ticket)->toBeInstanceOf(DragonTigerTicket::class)
        ->and(DragonTigerTicket::count())->toBe(1)
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
    $dragonTiger = DragonTigerGame::factory()->liveGame()->create();

    //$2500
    $member = Member::whereName('member_1')->whereType('member')->first();
    $member->group_id = $dragonTiger->game_table_id;
    $member->saveQuietly();

    //10, 10, 20, 10, 50
    DragonTigerTestHelper::setUpConditionForMember($member);

    $createTicket = function ($betOn, $betType, $expectedPayoutRate, $payout) use ($member) {
        $data = DragonTigerGameCreateTicketData::make(
            DragonTigerGameMemberBetData::make(
                member: $member,
                amount: 10,
                betOn: $betOn,
                betType: $betType,
                ip: '127.0.0.1'
            )
        );

        $ticket = DragonTigerGameCreateTicketAction::from($data);
        $ticket->refresh();
        expect($ticket->payout_rate)->toBe($expectedPayoutRate)
            ->and($ticket->payout)->toBe($payout);
    };

    $createTicket(DragonTigerCard::Tiger, DragonTigerCard::Tiger, 1.0, 40000);
    $createTicket(DragonTigerCard::Tiger, DragonTigerCard::Black, 0.90, 36000);
    $createTicket(DragonTigerCard::Tiger, DragonTigerCard::Red, 0.90, 36000);
    $createTicket(DragonTigerCard::Tie, DragonTigerCard::Tie, 7.0, 280000);
});
