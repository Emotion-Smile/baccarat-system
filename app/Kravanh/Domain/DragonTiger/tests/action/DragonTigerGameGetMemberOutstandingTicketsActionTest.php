<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetMemberOutstandingTicketsAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameMemberOutstandingTicketData;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use App\Kravanh\Domain\DragonTiger\Support\Balance;
use App\Kravanh\Domain\DragonTiger\tests\DragonTigerTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Support\Enums\Currency;
use Illuminate\Support\Str;

use function Pest\Laravel\seed;

test('verifies retrieval of member outstanding tickets in Dragon Tiger game', function () {

    setupUser(Currency::USD);
    seed(GameSeeder::class);

    $dragonTiger = DragonTigerGame::factory()->liveGame()->create();
    $member = DragonTigerTestHelper::member(groupId: $dragonTiger->game_table_id)->refresh();

    DragonTigerTicket::factory()
        ->forMember(
            member: $member,
            dragonTigerGame: $dragonTiger
        )
        ->count(5)->create();

    $report = (new DragonTigerGameGetMemberOutstandingTicketsAction(
        userId: $member->id,
        gameTableId: $member->getGameTableId()
    ));

    $rawTickets = $report->rawTickets();
    /**
     * @var DragonTigerGameMemberOutstandingTicketData $ticket
     */
    $ticket = $report->tickets()->first();

    /**
     * @var DragonTigerTicket $rawTicket
     */
    $rawTicket = $rawTickets->first();

    $betOnExpected = count(explode(' ', $ticket->betOn)) === 2 ?
        Str::title($rawTicket->bet_on.' '.$rawTicket->bet_type) :
        Str::title($rawTicket->bet_on);

    expect($rawTickets->count())->toBe(5)
        ->and($rawTickets->first())->toBeInstanceOf(DragonTigerTicket::class)
        ->and($rawTickets->first->toArray())->toHaveKeys([
            'id',
            'bet_on',
            'bet_type',
            'amount',
            'status',
            'created_at',
            'user_id',
            'dragon_tiger_game_id',
            'game',
            'user',
        ])
        ->and($ticket)->toBeInstanceOf(DragonTigerGameMemberOutstandingTicketData::class)
        ->and($ticket->ticketId)->toBe($rawTicket->id)
        ->and($ticket->gameNumber)->toBe('1_1')
        ->and($ticket->betOn)->toBe($betOnExpected)
        ->and($ticket->winLose)->toBe(Balance::format($rawTicket->amount, $rawTicket->user->currency).' * '.$rawTicket->payout_rate.': ???')
        ->and($ticket->status)->toBe('accepted')
        ->and($ticket->betTime)->toBe($rawTicket->created_at->format(config('kravanh.time_format')));

});
