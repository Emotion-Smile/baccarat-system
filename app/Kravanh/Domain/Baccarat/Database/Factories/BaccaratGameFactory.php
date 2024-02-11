<?php

namespace App\Kravanh\Domain\Baccarat\Database\Factories;

use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard;
use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Factories\Factory;

class BaccaratGameFactory extends Factory
{
    protected $model = BaccaratGame::class;

    private ?int $playerFirstCardPoints = null;
    private ?int $playerSecondCardPoints = null;
    private ?int $playerThirdCardPoints = null;
    private ?int $bankerFirstCardPoints = null;
    private ?int $bankerSecondCardPoints = null;
    private ?int $bankerThirdCardPoints = null;

    public function definition(): array
    {
        $this->playerFirstType = $this->faker->randomElement(['heart', 'diamond', 'club', 'spade']);
        $this->playerSecondType = $this->faker->randomElement(['heart', 'diamond', 'club', 'spade']);
        $this->playerThirdType = $this->faker->randomElement(['heart', 'diamond', 'club', 'spade']);

        $this->bankerFirstType = $this->faker->randomElement(['heart', 'diamond', 'club', 'spade']);
        $this->bankerSecondType = $this->faker->randomElement(['heart', 'diamond', 'club', 'spade']);
        $this->bankerThirdType = $this->faker->randomElement(['heart', 'diamond', 'club', 'spade']);

        //return [
        $paylaod = [
            'game_table_id' => BaccaratFactoryHelper::make()->gameTableId(),
            'user_id' => BaccaratFactoryHelper::make()->traderId(),
            'result_submitted_user_id' => BaccaratFactoryHelper::make()->traderId(),
            'round' => 1,
            'number' => 1,

            'player_first_card_value' => $this->makeFirstCardPoints($this->faker->numberBetween(0, 9), 'player'),
//            'player_first_card_type' => $this->faker->randomElement(['heart', 'diamond', 'club', 'spade']),
            'player_first_card_type' => $this->playerFirstType,
            'player_first_card_color' => $this->makeColor('player_first_card_type'),
            'player_first_card_points' => $this->playerFirstCardPoints,
            'player_second_card_value' => $this->makeSecondCardPoints($this->faker->numberBetween(0, 9), 'player'),
//            'player_second_card_type' => $this->faker->randomElement(['heart', 'diamond', 'club', 'spade']),
            'player_second_card_type' => $this->playerSecondType,
            'player_second_card_color' => $this->makeColor('player_second_card_type'),
            'player_second_card_points' => $this->playerSecondCardPoints,
            'player_third_card_value' => $this->makeThirdCardPoints($this->faker->numberBetween(0, 9), 'player'),
//            'player_third_card_type' => $this->faker->randomElement(['heart', 'diamond', 'club', 'spade']),
            'player_third_card_type' => $this->playerThirdType,
            'player_third_card_color' => $this->makeColor('player_third_card_type'),
            'player_third_card_points' => $this->playerThirdCardPoints,
            'player_total_points' => $this->playerFirstCardPoints + $this->playerSecondCardPoints + $this->playerThirdCardPoints,
            'player_points' => $this->playerFirstCardPoints + $this->playerSecondCardPoints + $this->playerThirdCardPoints,

            'banker_first_card_value' => $this->makeFirstCardPoints($this->faker->numberBetween(0, 9), 'banker'),
//            'banker_first_card_type' => $this->faker->randomElement(['heart', 'diamond', 'club', 'spade']),
            'banker_first_card_type' => $this->bankerFirstType,
            'banker_first_card_color' => $this->makeColor('banker_first_card_type'),
            'banker_first_card_points' => $this->bankerFirstCardPoints,
            'banker_second_card_value' => $this->makeSecondCardPoints($this->faker->numberBetween(0, 9), 'banker'),
//            'banker_second_card_type' => $this->faker->randomElement(['heart', 'diamond', 'club', 'spade']),
            'banker_second_card_type' => $this->bankerSecondType,
            'banker_second_card_color' => $this->makeColor('banker_second_card_type'),
            'banker_second_card_points' => $this->bankerSecondCardPoints,
            'banker_third_card_value' => $this->makeThirdCardPoints($this->faker->numberBetween(0, 9), 'banker'),
//            'banker_third_card_type' => $this->faker->randomElement(['heart', 'diamond', 'club', 'spade']),
            'banker_third_card_type' => $this->bankerThirdType,
            'banker_third_card_color' => $this->makeColor('banker_third_card_type'),
            'banker_third_card_points' => $this->bankerThirdCardPoints,
            'banker_total_points' => $this->bankerFirstCardPoints + $this->bankerSecondCardPoints + $this->bankerThirdCardPoints,
            'banker_points' => $this->bankerFirstCardPoints + $this->bankerSecondCardPoints + $this->bankerThirdCardPoints,

            'winner' => $this->makeWinner(),
            'started_at' => Carbon::now(),
            'closed_bet_at' => Carbon::now()->addSeconds(config('kravanh.baccarat_betting_interval')),
            'result_submitted_at' => Carbon::now()->addMinutes(2),
//            'statistic' => json_encode(['some_key' => 'some_value']), // adjust as needed
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()->addMinutes(2),
        ];

//        dd($paylaod);

        return $paylaod;
    }

    public function configure(): BaccaratGameFactory
    {
        return $this->sequence(function ($sequence) {
            $number = $sequence->index + 1;

            return ['number' => $number];
        });
    }

    protected function makeFirstCardPoints(int $value, string $type): int
    {
        if($type === 'player'){
            $this->playerFirstCardPoints = $value;
            return $this->playerFirstCardPoints;
        } else {
            $this->bankerFirstCardPoints = $value;
            return $this->bankerFirstCardPoints;
        }
    }

    protected function makeSecondCardPoints(int $value, string $type): int
    {
        if($type === 'player'){
            $this->playerSecondCardPoints = $value;
            return $this->playerSecondCardPoints;
        } else {
            $this->bankerSecondCardPoints = $value;
            return $this->bankerSecondCardPoints;
        }
    }

    protected function makeThirdCardPoints(int $value, string $type): int
    {
        if($type === 'player'){
            $this->playerThirdCardPoints = $value;
            return $this->playerThirdCardPoints;
        } else {
            $this->bankerThirdCardPoints = $value;
            return $this->bankerThirdCardPoints;
        }
    }

    protected function makeWinner(): Closure
    {
        return function (array $attribute) {
            $result = [];
            if ($attribute['player_first_card_value'] === $attribute['player_second_card_value']) {
                $result[] = 'player_pair';
            }
            if ($attribute['banker_first_card_value'] > $attribute['banker_second_card_value']) {
                $result[] = 'banker_pair';
            }
            if ($attribute['player_points'] === $attribute['banker_points']) {
                $result[] = 'tie';
            }
            if ($attribute['player_points'] > $attribute['banker_points']) {
                $result[] = 'player';
            }
            if ($attribute['player_points'] < $attribute['banker_points']) {
                $result[] = 'banker';
            }
            if ($attribute['player_third_card_value'] || $attribute['banker_third_card_value']) {
                $result[] = 'big';
            }
            if (!$attribute['player_third_card_value'] && !$attribute['banker_third_card_value']) {
                $result[] = 'small';
            }

            return $result;
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
            return BaccaratCard::getColor($attribute[$attributeName]);
        };
    }

//    protected function makeColor(string $attributeName): Closure
//    {
//        return function (array $attribute) use ($attributeName) {
//            return BaccaratCard::getColor($attribute[$attributeName]);
//        };
//    }

    public function liveGame(): BaccaratGameFactory
    {

        return $this->state(function (array $attributes) {

            return [
                'result_submitted_at' => null,
                'result_submitted_user_id' => null,
                'player_first_card_value' => null,
                'player_first_card_type' => null,
                'player_first_card_color' => null,
                'player_first_card_points' => null,
                'player_second_card_value' => null,
                'player_second_card_type' => null,
                'player_second_card_color' => null,
                'player_second_card_points' => null,
                'player_third_card_value' => null,
                'player_third_card_type' => null,
                'player_third_card_color' => null,
                'player_third_card_points' => null,
                'player_total_points' => null,
                'player_points' => null,
                'banker_first_card_value' => null,
                'banker_first_card_type' => null,
                'banker_first_card_color' => null,
                'banker_first_card_points' => null,
                'banker_second_card_value' => null,
                'banker_second_card_type' => null,
                'banker_second_card_color' => null,
                'banker_second_card_points' => null,
                'banker_third_card_value' => null,
                'banker_third_card_type' => null,
                'banker_third_card_color' => null,
                'banker_third_card_points' => null,
                'banker_total_points' => null,
                'banker_points' => null,
                'winner' => null,
            ];
        });
    }
}
