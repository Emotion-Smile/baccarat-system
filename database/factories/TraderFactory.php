<?php

namespace Database\Factories;

use App\Kravanh\Domain\User\Models\Trader;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class TraderFactory extends Factory
{
    use FactoryHelper;

    protected $model = Trader::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->userName,
            'password' => Hash::make('password'),
            'type' => UserType::TRADER,
            'environment_id' => $this->getEnvId(),
            'group_id' => $this->getGroupId()
        ];
    }

    public function dragonTigerTrader(): TraderFactory
    {
        return $this->state(function (array $attribute) {
            return [
                'two_factor_secret' => 'dragon_tiger'
            ];
        });
    }

    public function baccaratTrader(): TraderFactory
    {
        return $this->state(function (array $attribute) {
            return [
                'two_factor_secret' => 'baccarat'
            ];
        });
    }

}
