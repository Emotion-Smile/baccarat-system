<?php

namespace Database\Factories;

use App\Kravanh\Domain\Environment\Models\Environment;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnvironmentFactory extends Factory
{

    protected $model = Environment::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'domain' => $this->faker->domainName
        ];
    }
}
