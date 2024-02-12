<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetMemberTotalBetAmountAction;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\User\Models\Member;

test('it can get member total bet amount on game', function () {

    $member = Member::factory()->create();
    $game = BaccaratGame::factory()->create();

    BaccaratTicket::factory([
        'user_id' => $member->id,
        'dragon_tiger_game_id' => $game->id,
        'amount' => 4_000
    ])->count(4)->create();

    $totalBet = (new BaccaratGameGetMemberTotalBetAmountAction())(gameId: $game->id, userId: $member->id);
    expect($totalBet)->toBe(16_000);

});
