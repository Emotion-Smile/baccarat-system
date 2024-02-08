<?php

namespace Database\Factories;

use App\Kravanh\Domain\User\Models\Company;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserCompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'password' => Hash::make('password'),
            'phone' => $this->faker->phoneNumber,
            'type' => UserType::COMPANY
        ];
    }
}
