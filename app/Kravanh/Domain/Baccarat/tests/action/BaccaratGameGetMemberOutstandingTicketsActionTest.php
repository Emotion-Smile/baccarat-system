<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetMemberOutstandingTicketsAction;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameMemberOutstandingTicketData;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\Baccarat\Support\Balance;
use App\Kravanh\Domain\Baccarat\tests\BaccaratTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Support\Enums\Currency;
use Illuminate\Support\Str;

use function Pest\Laravel\seed;

test('verifies retrieval of member outstanding tickets in Dragon Tiger game', function () {

    setupUser(Currency::USD);
    seed(GameSeeder::class);

    $dragonTiger = BaccaratGame::factory()->liveGame()->create();
    $member = BaccaratTestHelper::member(groupId: $dragonTiger->game_table_id)->refresh();

    BaccaratTicket::factory()
        ->forMember(
            member: $member,
            dragonTigerGame: $dragonTiger
        )
        ->count(5)->create();

    $report = (new BaccaratGameGetMemberOutstandingTicketsAction(
        userId: $member->id,
        gameTableId: $member->getGameTableId()
    ));

    $rawTickets = $report->rawTickets();
    /**
     * @var BaccaratGameMemberOutstandingTicketData $ticket
     */
    $ticket = $report->tickets()->first();

    /**
     * @var BaccaratTicket $rawTicket
     */
    $rawTicket = $rawTickets->first();

    $betOnExpected = count(explode(' ', $ticket->betOn)) === 2 ?
        Str::title($rawTicket->bet_on.' '.$rawTicket->bet_type) :
        Str::title($rawTicket->bet_on);

    expect($rawTickets->count())->toBe(5)
        ->and($rawTickets->first())->toBeInstanceOf(BaccaratTicket::class)
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
        ->and($ticket)->toBeInstanceOf(BaccaratGameMemberOutstandingTicketData::class)
        ->and($ticket->ticketId)->toBe($rawTicket->id)
        ->and($ticket->gameNumber)->toBe('1_1')
        ->and($ticket->betOn)->toBe($betOnExpected)
        ->and($ticket->winLose)->toBe(Balance::format($rawTicket->amount, $rawTicket->user->currency).' * '.$rawTicket->payout_rate.': ???')
        ->and($ticket->status)->toBe('accepted')
        ->and($ticket->betTime)->toBe($rawTicket->created_at->format(config('kravanh.time_format')));

});
