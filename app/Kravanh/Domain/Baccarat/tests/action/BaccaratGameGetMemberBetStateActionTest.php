<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetMemberBetStateAction;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\Game\Models\GameTable;
use App\Kravanh\Domain\User\Models\Member;

test('it can get member bet state', function () {

    $gameTable = GameTable::factory()->create();
    /**
     * @var BaccaratGame $dragonTigerGame
     */
    $dragonTigerGame = BaccaratGame::factory(['game_table_id' => $gameTable->id])->liveGame()->create();
    $member = Member::factory(['group_id' => $gameTable->id])->create();

    $betOns = ['tie_tie', 'tie_tie', 'dragon_dragon', 'dragon_dragon', 'tiger_tiger', 'tiger_tiger', 'dragon_red', 'dragon_red', 'tiger_red', 'tiger_red'];

    foreach ($betOns as $betOn) {
        $bet = explode('_', $betOn);
        BaccaratTicket::factory([
            'user_id' => $member->id,
            'game_table_id' => $gameTable->id,
            'dragon_tiger_game_id' => $dragonTigerGame->id,
            'bet_on' => $bet[0],
            'bet_type' => $bet[1],
            'amount' => 4000,
        ])->create();
    }

    $betState = (new BaccaratGameGetMemberBetStateAction())(
        userId: $member->id,
        currency: $member->currency,
        dragonTigerGameId: $dragonTigerGame->id
    );

    expect($betState)
        ->toHaveKeys(['tie', 'dragon', 'tiger', 'dragon_red', 'tiger_red']);

    foreach ($betState as $bet) {
        expect($bet)->toMatchArray([
            'amount' => 8000,
            'currency' => 'KHR',
        ])->and($bet['label'])->toContain('8 ពាន់');
        //->and(str_replace('&nbsp;', ' ', htmlentities($bet['label'])))->toContain('8 ពាន់');
    }

});
