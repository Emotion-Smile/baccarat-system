<?php

use App\Kravanh\Domain\Integration\Actions\T88\CreateUserAction;
use App\Kravanh\Domain\Integration\DataTransferObject\T88\CreateGameConditionData;
use App\Kravanh\Domain\User\Models\Member;
use Illuminate\Support\Facades\Http;

it(
    'will throws error message when user not created success on T88.', 
    function () {
        Http::fake([
            'dev-loto1.kravanh.co/api/v1/*' => Http::response([
                'message' => 'The name has already been taken.',
                'errors' => [
                    'name' => [
                        'The name has already been taken.'
                    ]
                ]
            ], 422)
        ]);

        $user = Member::factory()->create();

        expect(
            fn() => (new CreateUserAction)(
                user: $user,
                token: '69f9a7a09538b15a6e4b05f9d093b406',
                createGameConditionData: new CreateGameConditionData(
                    userId: $user->id,
                    gameType: 'LOTTO-12',
                    condition: [
                        'commission' => 0,
                        'down_line_share' => 60,
                        'bet_limit' => 800000,
                        'win_limit' => 4000000,
                        'minimum_bet' => 4000,
                        'maximum_bet' => 400000
                    ] 
                )
            )
        )
            ->toThrow(ErrorException::class, 'The name has already been taken.');
    }
)->skip();