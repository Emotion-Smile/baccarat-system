<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetMemberTotalBetAmountAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use App\Kravanh\Domain\User\Models\Member;

test('it can get member total bet amount on game', function () {

    $member = Member::factory()->create();
    $game = DragonTigerGame::factory()->create();

    DragonTigerTicket::factory([
        'user_id' => $member->id,
        'dragon_tiger_game_id' => $game->id,
        'amount' => 4_000
    ])->count(4)->create();

    $totalBet = (new DragonTigerGameGetMemberTotalBetAmountAction())(gameId: $game->id, userId: $member->id);
    expect($totalBet)->toBe(16_000);

});
