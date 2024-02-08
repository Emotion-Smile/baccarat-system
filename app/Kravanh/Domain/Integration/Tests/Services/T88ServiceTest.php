<?php

use App\Kravanh\Domain\Integration\Services\T88Service;
use App\Kravanh\Domain\User\Models\MasterAgent;

it('can get game conditions.', function () {
    $user = MasterAgent::factory()->create();

    $condition = app(T88Service::class)->getUserGameCondition(
        user: $user,
        gameType: 'LOTTO-12' 
    );

    expect($condition)->toMatchArray([
        'id' => 8,
        'game_type' => 'LOTTO-12' ,
        'condition_mode' => false,
        'commission' => 0,
        'down_line_share' => 0,
        'hold_share' => 70,
        'taken_share' => 70,
        'match_limit' => 800000,
        'win_limit' => 4000000,
        'maximum_bet_per_ticket' => 400000,
        'minimum_bet_per_ticket' => 4000,
        'enabled' => true
    ]);
})
    ->skip();