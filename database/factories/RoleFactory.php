<?php

namespace Database\Factories;

use App\Kravanh\Domain\User\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
class RoleFactory extends Factory
{
    protected $model = Role::class;


    public function definition(): array
    {
        return [
            'name' => $this->faker->title,
            'label' => $this->faker->name
        ];
    }

}
