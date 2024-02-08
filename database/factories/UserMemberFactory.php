<?php

namespace Database\Factories;

use App\Kravanh\Domain\User\Models\Agent;
use App\Kravanh\Domain\User\Models\MasterAgent;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Models\Senior;
use App\Kravanh\Domain\User\Models\SuperSenior;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserMemberFactory extends Factory
{
    use FactoryHelper;

    protected $model = Member::class;

    public function definition(): array
    {
        $fields = [
            'super_senior' => fn (array $attr) => SuperSenior::factory(),
            'senior' => fn (array $attr) => Senior::factory([
                'super_senior' => $attr['super_senior'],
            ]),
            'master_agent' => fn (array $attr) => MasterAgent::factory([
                'super_senior' => $attr['super_senior'],
                'senior' => $attr['senior'],
            ]),
            'agent' => fn (array $attr) => Agent::factory([
                'super_senior' => $attr['super_senior'],
                'senior' => $attr['senior'],
                'master_agent' => $attr['master_agent'],
            ]),
            'condition' => [
                'my_position_share' => 50,
                'down_line_share' => 0,
                'match_limit' => 10000,
                'credit_limit' => 20000,
                'minimum_bet_per_ticket' => 1000,
                'maximum_bet_per_ticket' => 10000,
            ],

        ];

        return array_merge_recursive($this->generalUserFields(UserType::MEMBER), $fields);

    }
}
