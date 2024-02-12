<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetMemberTicketsAction;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameMemberTicketData;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\Baccarat\Support\DateFilter;
use App\Kravanh\Domain\User\Models\Member;
use function Pest\Laravel\assertDatabaseCount;

test('it can get member\'s tickets', function () {

    $member = Member::factory()->create();

    $game = BaccaratGame::factory(['game_table_id' => 1])->create();
    BaccaratTicket::factory(['game_table_id' => 1, 'dragon_tiger_game_id' => $game->id, 'user_id' => $member->id])->count(5)->create();

    $gameTable2 = BaccaratGame::factory(['game_table_id' => 2])->create();
    BaccaratTicket::factory(['game_table_id' => 2, 'dragon_tiger_game_id' => $gameTable2->id, 'user_id' => $member->id])->count(5)->create();

    $gameLive = BaccaratGame::factory(['game_table_id' => 1])->liveGame()->create();
    BaccaratTicket::factory(['game_table_id' => 1, 'dragon_tiger_game_id' => $gameLive->id, 'user_id' => $member->id])->count(5)->create();


    $tickets = (new BaccaratGameGetMemberTicketsAction())(
        userId: $member->id
    );

    assertDatabaseCount(BaccaratTicket::class, 15);
    expect($tickets->count())->toBe(10);

    $tickets = (new BaccaratGameGetMemberTicketsAction())(
        userId: $member->id,
        gameTableId: 1
    );

    expect($tickets->count())->toBe(5)
        ->and($tickets->toReport()[0])->toBeInstanceOf(BaccaratGameMemberTicketData::class);

    $tickets = (new BaccaratGameGetMemberTicketsAction())(
        userId: $member->id,
        gameTableId: 1,
        filterMode: DateFilter::Yesterday
    );

    expect($tickets->count())->toBe(0);

});
