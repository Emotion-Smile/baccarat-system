<?php

namespace Database\Factories;

use App\Kravanh\Domain\User\Models\SuperSenior;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSuperSeniorFactory extends Factory
{
    use FactoryHelper;

    protected $model = SuperSenior::class;

    public function definition(): array
    {
        return array_merge(
            $this->generalUserFields(UserType::SUPER_SENIOR),
            ['condition' => [
                'my_position_share' => 10,
                'down_line_share' => 90
            ]]
        );
    }
}
