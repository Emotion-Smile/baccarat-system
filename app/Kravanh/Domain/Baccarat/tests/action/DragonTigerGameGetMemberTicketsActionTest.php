<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetMemberTicketsAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameMemberTicketData;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use App\Kravanh\Domain\DragonTiger\Support\DateFilter;
use App\Kravanh\Domain\User\Models\Member;
use function Pest\Laravel\assertDatabaseCount;

test('it can get member\'s tickets', function () {

    $member = Member::factory()->create();

    $game = DragonTigerGame::factory(['game_table_id' => 1])->create();
    DragonTigerTicket::factory(['game_table_id' => 1, 'dragon_tiger_game_id' => $game->id, 'user_id' => $member->id])->count(5)->create();

    $gameTable2 = DragonTigerGame::factory(['game_table_id' => 2])->create();
    DragonTigerTicket::factory(['game_table_id' => 2, 'dragon_tiger_game_id' => $gameTable2->id, 'user_id' => $member->id])->count(5)->create();

    $gameLive = DragonTigerGame::factory(['game_table_id' => 1])->liveGame()->create();
    DragonTigerTicket::factory(['game_table_id' => 1, 'dragon_tiger_game_id' => $gameLive->id, 'user_id' => $member->id])->count(5)->create();


    $tickets = (new DragonTigerGameGetMemberTicketsAction())(
        userId: $member->id
    );

    assertDatabaseCount(DragonTigerTicket::class, 15);
    expect($tickets->count())->toBe(10);

    $tickets = (new DragonTigerGameGetMemberTicketsAction())(
        userId: $member->id,
        gameTableId: 1
    );

    expect($tickets->count())->toBe(5)
        ->and($tickets->toReport()[0])->toBeInstanceOf(DragonTigerGameMemberTicketData::class);

    $tickets = (new DragonTigerGameGetMemberTicketsAction())(
        userId: $member->id,
        gameTableId: 1,
        filterMode: DateFilter::Yesterday
    );

    expect($tickets->count())->toBe(0);

});
