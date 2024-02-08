<?php

namespace App\Kravanh\Domain\Integration\Actions\T88;

use App\Kravanh\Domain\Integration\DataTransferObject\T88\UpdateGameConditionData;
use App\Kravanh\Domain\Integration\Exceptions\T88Exception;
use App\Kravanh\Domain\Integration\Supports\Helpers\HttpJsonHelper;
use App\Kravanh\Domain\Integration\Supports\Traits\T88\HasApi;
use App\Models\User;

class UpdateUserAction
{
    use HasApi;

    public function __invoke(
        string $token,
        User $user,
        UpdateGameConditionData $updateGameConditionData
    ): void
    {
        $response = HttpJsonHelper::prepare()
            ->authorization($token)
            ->put(
                url: $this->requestUrl("/update-condition/{$user->name}"),
                data: $this->requestBody($updateGameConditionData)   
            );
        
        if($response->failed()) {
            throw new T88Exception($response->object()?->message);
        }
    }

    protected function requestBody( 
        UpdateGameConditionData $updateGameConditionData
    ): array
    {
        $gameType = $updateGameConditionData->gameType;
        $condition = $updateGameConditionData->condition;

        return [
            'game_type' => $gameType,
            'bet_limit' => $condition['bet_limit'],
            'win_limit' => $condition['win_limit'],
            'minimum_bet' => $condition['minimum_bet'],
            'maximum_bet' => $condition['maximum_bet']  
        ];
    }
}