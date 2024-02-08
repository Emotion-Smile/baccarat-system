<?php

namespace App\Kravanh\Domain\Integration\Nova\Http\Requests\T88;

use App\Kravanh\Domain\Integration\Supports\Nova\T88GameConditionNovaActionFields;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class SaveGameConditionRequest extends FormRequest
{
    public function rules(): array
    {
        $upLineCondition = (new T88GameConditionNovaActionFields)->upLineCondition();

        $downLineShareRules = [];

        if(!$this->user()->isAgent()) {
            $downLineShareRules = [
                'down_line_share' => [
                    'required',
                    "lte:{$upLineCondition['down_line_share']}"
                ]
            ];
        }

        return [
            'game_type' => [
                'required'
            ],

            'minimum_bet' => [
                'required',
                'numeric',
                $upLineCondition['minimum_bet'] ? "gte:{$upLineCondition['minimum_bet']}" : null
            ],
            
            'maximum_bet' => [
                'required',
                'numeric',
                $upLineCondition['minimum_bet'] ? "gt:{$upLineCondition['minimum_bet']}" : null,
                $upLineCondition['maximum_bet'] ? "lte:{$upLineCondition['maximum_bet']}" : null,
            ],

            'bet_limit' => [
                'required',
                'numeric',
                'gt:0',
                $upLineCondition['bet_limit'] ? "lte:{$upLineCondition['bet_limit']}" : null
            ],

            'win_limit' => [
                'required',
                'numeric',
                'gt:0',
                $upLineCondition['win_limit'] ? "lte:{$upLineCondition['win_limit']}" : null
            ],

            ...$downLineShareRules
        ];
    }

    public function getUser(): User
    {
        return User::find($this->resources);
    }
}