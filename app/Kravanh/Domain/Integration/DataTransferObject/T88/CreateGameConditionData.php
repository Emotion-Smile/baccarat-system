<?php

namespace App\Kravanh\Domain\Integration\DataTransferObject\T88;

use App\Kravanh\Domain\Integration\Models\T88GameCondition;
use App\Kravanh\Domain\Integration\Nova\Http\Requests\T88\SaveGameConditionRequest;
use App\Models\User;
use Laravel\Nova\Fields\ActionFields;

class CreateGameConditionData
{
    public function __construct(
        public int $userId,
        public string $gameType,
        public array $condition
    )
    {}

    public static function new(
        int $userId,
        string $gameType,
        array $condition
    ): CreateGameConditionData
    {
        return new static(
            userId: $userId,
            gameType: $gameType,
            condition: $condition
        );
    }

    public static function fromNovaAction(
        User $user,
        ActionFields $fields
    ): CreateGameConditionData
    {
        return new static(
            userId: $user->id,
            gameType: $fields->game_type,
            condition: [
                'commission' => $fields->commission,
                'down_line_share' => $fields->down_line_share,
                'bet_limit' => $fields->bet_limit,
                'win_limit' => $fields->win_limit,
                'minimum_bet' =>  $fields->minimum_bet,
                'maximum_bet' =>  $fields->maximum_bet
            ]
        );
    }

    public static function fromRequest(
        User $user, 
        SaveGameConditionRequest $request
    ): CreateGameConditionData 
    {
        return new static(
            userId: $user->id,
            gameType: $request->game_type,
            condition: [
                'commission' => $request->commission,
                'down_line_share' => $request->down_line_share,
                'bet_limit' => $request->bet_limit,
                'win_limit' => $request->win_limit,
                'minimum_bet' => $request->minimum_bet,
                'maximum_bet' => $request->maximum_bet
            ]
        );
    }

    public function noShare(): CreateGameConditionData
    {
        $this->condition['down_line_share'] = 0;

        return $this;
    } 

    public function noCommission(): CreateGameConditionData
    {
        $this->condition['commission'] = 0;

        return $this;
    }
}