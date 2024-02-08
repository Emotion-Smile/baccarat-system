<?php

use App\Kravanh\Domain\Integration\Actions\T88\CreateGameConditionAction;
use App\Kravanh\Domain\Integration\DataTransferObject\T88\CreateGameConditionData;
use App\Kravanh\Domain\User\Models\Agent;

it('can create game condition correctly.', function () {
    $user = Agent::factory()->create();

    $gameCondition = (new CreateGameConditionAction)(
        new CreateGameConditionData(
            userId: $user->id,
            gameType: 'LOTTO-12',
            condition: [
                'commission' => 0,
                'down_line_share' => 70,
                'bet_limit' => 800000,
                'win_limit' => 4000000,
                'minimum_bet' => 4000,
                'maximum_bet' => 400000
            ]
        )
    );

    expect($gameCondition->user_id)->toBe($user->id);
    expect($gameCondition->game_type)->toBe('LOTTO-12');

    expect($gameCondition->condition)->toBeArray()
        ->toMatchArray([
            'commission' => 0,
            'down_line_share' => 70,
            'bet_limit' => 800000,
            'win_limit' => 4000000,
            'minimum_bet' => 4000,
            'maximum_bet' => 400000
        ]);
})
    ->skip();