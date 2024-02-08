<?php

namespace Database\Factories;

use App\Kravanh\Domain\User\Models\MasterAgent;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserMasterAgentFactory extends Factory
{
    use FactoryHelper;

    protected $model = MasterAgent::class;

    public function definition(): array
    {
        return array_merge(
            $this->generalUserFields(UserType::MASTER_AGENT),
            [
                'condition' => [
                    'my_position_share' => 10,
                    'down_line_share' => 90,
                    'match_limit' => 10000,
                    'credit_limit' => 20000,
                    'minimum_bet_per_ticket' => 1000,
                    'maximum_bet_per_ticket' => 10000,
                ]
            ]
        );
    }
}
