<?php

namespace App\Kravanh\Domain\Integration\Supports\Nova;

use App\Kravanh\Domain\Integration\Contracts\Services\T88Contract;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use KravanhEco\Balance\Balance;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;

class T88GameConditionNovaActionFields
{
    public const GAME_TYPE = 'LOTTO-12';

    public function __invoke(User $user): array
    {
        $upLineCondition = $this->upLineCondition();
        $downLineCondition = $this->downLineCondition($user);

        $onUpdate = $user->hasT88GameCondition(self::GAME_TYPE);
        
        return [
            Select::make('Game Type', 'game_type')
                ->options([
                    'LOTTO-12' => 'Yuki'
                ])
                ->rules([
                    'required'
                ])
                ->default(self::GAME_TYPE),

            // Number::make('Commission', 'commission')
            //     ->default($downLineCondition['commission'] ?? 0)
            //     ->rules(
            //         'required',
            //         'numeric',
            //         $upLineCondition['commission'] ? "lte:{$upLineCondition['commission']}" : null
            //     )
            //     ->readonly($onUpdate),

            Hidden::make('Commission', 'commission')
                ->default($downLineCondition['commission'] ?? 0),

            ...$this->downLineShareField(
                upLineCondition: $upLineCondition, 
                downLineCondition: $downLineCondition, 
                onUpdate: $onUpdate
            ),

            Balance::make('Min Bet', 'minimum_bet')
                ->default($downLineCondition['minimum_bet'])
                ->rules(
                    'required',
                    'numeric',
                    $upLineCondition['minimum_bet'] ? "gte:{$upLineCondition['minimum_bet']}" : null
                )
                ->help($upLineCondition['minimum_bet'] ? "Current from upline: {$upLineCondition['minimum_bet']}" : null),
        
            Balance::make('Max Bet (O/U & O/E)', 'maximum_bet')
                ->default($downLineCondition['maximum_bet'])
                ->rules(
                    'required',
                    'numeric',
                    $upLineCondition['minimum_bet'] ? "gt:{$upLineCondition['minimum_bet']}" : null,
                    $upLineCondition['maximum_bet'] ? "lte:{$upLineCondition['maximum_bet']}" : null,
                )
                ->help($upLineCondition['maximum_bet'] ? "Current from upline: {$upLineCondition['maximum_bet']}" : null),

            Balance::make('Bet Limit', 'bet_limit')
                ->default($downLineCondition['bet_limit'])
                ->rules(
                    'required',
                    'numeric',
                    'gt:0',
                    $upLineCondition['bet_limit'] ? "lte:{$upLineCondition['bet_limit']}" : null
                )
                ->help($upLineCondition['bet_limit'] ? "Current from upline: {$upLineCondition['bet_limit']}" : null),

            Balance::make('Win Limit Per Day', 'win_limit')
                ->default($downLineCondition['win_limit'])
                ->rules(
                    'required',
                    'numeric',
                    'gt:0',
                    $upLineCondition['win_limit'] ? "lte:{$upLineCondition['win_limit']}" : null
                )
                ->help($upLineCondition['win_limit'] ? "Current from upline: {$upLineCondition['win_limit']}" : null),
        ];
    }

    protected function downLineShareField(
        array $upLineCondition, 
        array $downLineCondition, 
        bool $onUpdate
    ): array
    {
        $user = request()->user();

        if($user->type->is(UserType::AGENT)) return [];

        return [
            Number::make('Down line Share', 'down_line_share')
                ->default($downLineCondition['down_line_share'])
                ->rules(
                    'required',
                    "lte:{$upLineCondition['down_line_share']}"
                )
                ->readonly($onUpdate)
                ->help("Current: {$upLineCondition['down_line_share']}%")
        ];
    }

    public function upLineCondition(): array
    {
        $condition = app(T88Contract::class)
            ->getUserGameCondition(
                user: request()->user(),
                gameType: self::GAME_TYPE 
            );

        return [
            'commission' => $condition['commission'] ?? null,
            'down_line_share' => $condition['down_line_share'] ?? 100,
            'bet_limit' => $condition['bet_limit'] ?? null,
            'win_limit' => $condition['win_limit'] ?? null,
            'minimum_bet' => $condition['minimum_bet'] ?? null,
            'maximum_bet' => $condition['maximum_bet'] ?? null 
        ];
    }

    protected function downLineCondition(User $user): array
    {
        $condition = $user->t88GameCondition(self::GAME_TYPE)?->condition;

        return [
            'commission' => $condition['commission'] ?? null,
            'down_line_share' => $condition['down_line_share'] ?? null,
            'bet_limit' => $condition['bet_limit'] ?? null,
            'win_limit' => $condition['win_limit'] ?? null,
            'minimum_bet' => $condition['minimum_bet'] ?? null,
            'maximum_bet' => $condition['maximum_bet'] ?? null 
        ];
    }
}