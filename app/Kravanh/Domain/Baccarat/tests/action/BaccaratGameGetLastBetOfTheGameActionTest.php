<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetLastBetOfTheGameAction;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\User\Models\Member;

test('it retrieves bets from the most recent dragon tiger game', function () {

    $member = Member::factory()->create();

    $dragonTigerGame = BaccaratGame::factory()->create();

    BaccaratTicket::factory([
        'dragon_tiger_game_id' => $dragonTigerGame->id,
        'user_id' => $member->id,
    ])
        ->count(4)
        ->create();

    $bets = (new BaccaratGameGetLastBetOfTheGameAction())($member->id, $member->currency->value);

    expect($bets)->toBeArray()
        ->and(count($bets))->toBe(4)
        ->and($bets[0])->toHaveKeys(['amount', 'betOn', 'betType']);

});
