<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetLastBetOfTheGameAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use App\Kravanh\Domain\User\Models\Member;

test('it retrieves bets from the most recent dragon tiger game', function () {

    $member = Member::factory()->create();

    $dragonTigerGame = DragonTigerGame::factory()->create();

    DragonTigerTicket::factory([
        'dragon_tiger_game_id' => $dragonTigerGame->id,
        'user_id' => $member->id,
    ])
        ->count(4)
        ->create();

    $bets = (new DragonTigerGameGetLastBetOfTheGameAction())($member->id, $member->currency->value);

    expect($bets)->toBeArray()
        ->and(count($bets))->toBe(4)
        ->and($bets[0])->toHaveKeys(['amount', 'betOn', 'betType']);

});
