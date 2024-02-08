<?php

namespace App\Kravanh\Domain\Integration\Actions\T88;

use App\Kravanh\Domain\Integration\DataTransferObject\T88\CreateGameConditionData;
use App\Kravanh\Domain\Integration\Exceptions\T88Exception;
use App\Kravanh\Domain\Integration\Supports\Helpers\HttpJsonHelper;
use App\Kravanh\Domain\Integration\Supports\Traits\T88\HasApi;
use App\Models\User;
use Illuminate\Support\Str;

class CreateUserAction
{
    use HasApi;

    public function __invoke(
        string $token,
        User $user,
        CreateGameConditionData $createGameConditionData
    ): void
    {
        $userType = Str::of($user->type->value)->camel()->kebab(); 

        $response = HttpJsonHelper::prepare()
            ->authorization($token)
            ->post(
                url: $this->requestUrl("/create/{$userType}"),
                data: $this->requestBody(
                    user: $user, 
                    createGameConditionData: $createGameConditionData
                )   
            );

        if($response->failed()) {
            throw new T88Exception($response->object()?->message);
        }
    }

    protected function requestBody(
        User $user, 
        CreateGameConditionData $createGameConditionData
    ): array
    {
        $gameType = $createGameConditionData->gameType;
        $condition = $createGameConditionData->condition;

        return [
            'name' => $user->name . '.f88',
            'original_name' => $user->name,
            'currency' => $user->currency,
            'contact' => $user->phone,
            'game_type' => $gameType,
            'commission' => $condition['commission'],
            'down_line_share' => $condition['down_line_share'],
            'bet_limit' => $condition['bet_limit'],
            'win_limit' => $condition['win_limit'],
            'minimum_bet' => $condition['minimum_bet'],
            'maximum_bet' => $condition['maximum_bet']  
        ];
    }
}