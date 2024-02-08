<?php

namespace App\Kravanh\Domain\Integration\DataTransferObject\T88;

use App\Kravanh\Domain\Integration\Nova\Http\Requests\T88\SaveGameConditionRequest;
use App\Models\User;
use Laravel\Nova\Fields\ActionFields;

class UpdateGameConditionData
{
    public function __construct(
        public readonly int $userId,
        public readonly string $gameType,
        public readonly array $condition
    )
    {}

    public static function fromNovaAction(
        User $user,
        ActionFields $fields
    ): UpdateGameConditionData
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
    ): UpdateGameConditionData 
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
}