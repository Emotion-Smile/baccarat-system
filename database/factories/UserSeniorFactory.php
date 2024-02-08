<?php

namespace Database\Factories;

use App\Kravanh\Domain\User\Models\Senior;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSeniorFactory extends Factory
{
    use FactoryHelper;

    protected $model = Senior::class;

    public function definition(): array
    {
        return array_merge(
            $this->generalUserFields(UserType::SENIOR),
            ['condition' => [
                'my_position_share' => 10,
                'down_line_share' => 90
            ]]
        );
    }
}
