<?php

namespace App\Kravanh\Domain\DragonTiger\Database\Factories;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerCard;
use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Factories\Factory;

class DragonTigerGameFactory extends Factory
{
    protected $model = DragonTigerGame::class;

    public function definition(): array
    {

        return [
            'game_table_id' => DragonTigerFactoryHelper::make()->gameTableId(),
            'user_id' => DragonTigerFactoryHelper::make()->traderId(),
            'result_submitted_user_id' => DragonTigerFactoryHelper::make()->traderId(),
            'round' => 1,
            'number' => 1,
            'dragon_result' => $this->faker->numberBetween(1, 13),
            'dragon_type' => $this->faker->randomElement(['heart', 'diamond', 'club', 'spade']),
            'dragon_color' => $this->makeColor('dragon_type'),
            'dragon_range' => $this->makeRangLevel('dragon_result'),
            'tiger_result' => $this->faker->numberBetween(1, 13),
            'tiger_type' => $this->faker->randomElement(['heart', 'diamond', 'club', 'spade']),
            'tiger_color' => $this->makeColor('tiger_type'),
            'tiger_range' => $this->makeRangLevel('tiger_result'),
            'winner' => $this->makeWinner(),
            'started_at' => Carbon::now(),
            'closed_bet_at' => Carbon::now()->addSeconds(config('kravanh.dragon_tiger_betting_interval')),
            'result_submitted_at' => Carbon::now()->addMinutes(2),
            'statistic' => json_encode(['some_key' => 'some_value']), // adjust as needed
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()->addMinutes(2),
        ];

    }

    public function configure(): DragonTigerGameFactory
    {
        return $this->sequence(function ($sequence) {
            $number = $sequence->index + 1;

            return ['number' => $number];
        });
    }

    protected function makeWinner(): Closure
    {
        return function (array $attribute) {
            if ($attribute['dragon_result'] === $attribute['tiger_result']) {
                return 'tie';
            }
            if ($attribute['dragon_result'] > $attribute['tiger_result']) {
                return 'dragon';
            } else {
                return 'tiger';
            }
        };
    }

    protected function makePair(): Closure
    {
        return function (array $attribute) {
            $result = [];
            if ($attribute['player_card_1'] === $attribute['player_card_2']) {
                $result[] = 'p_pair';
            }
            if ($attribute['banker_card_1'] === $attribute['banker_card_2']) {
                $result[] = 'b_pair';
            }
            return $result;
        };
    }

    protected function makeBigSmall(): Closure
    {
        return function (array $attribute) {
            if ($attribute['player_card_3'] || $attribute['banker_card_3']) {
                return "big";
            } else {
                return "small";
            }
        };
    }

    protected function makeColor(string $attributeName): Closure
    {
        return function (array $attribute) use ($attributeName) {
            return DragonTigerCard::getColor($attribute[$attributeName]);
        };
    }

    protected function makeRangLevel(string $attributeName): Closure
    {
        return function (array $attribute) use ($attributeName) {
            return DragonTigerCard::getRange($attribute[$attributeName]);
        };
    }

    public function liveGame(): DragonTigerGameFactory
    {

        return $this->state(function (array $attributes) {

            return [
                'result_submitted_at' => null,
                'result_submitted_user_id' => null,
                'dragon_result' => null,
                'dragon_type' => null,
                'dragon_color' => null,
                'dragon_range' => null,
                'tiger_result' => null,
                'tiger_type' => null,
                'tiger_color' => null,
                'tiger_range' => null,
                'winner' => null,
            ];
        });
    }
}
