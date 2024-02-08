<?php

namespace Database\Factories;

use App\Kravanh\Domain\Environment\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    protected $model = Group::class;

    public function definition(): array
    {
        return [
            'environment_id' => 1,
            'name' => $this->faker->name,
            'streaming_link' => 'https://www.youtube.com/embed/ai4yT-qLuEU',
            'streaming_server_ip' => $this->faker->ipv4,
            'css_style' => 'bg-channel-3',
            'meron' => 'meron',
            'wala' => 'wala',
            'order' => 1
        ];
    }

    public function setRedBlue(): GroupFactory
    {
        return $this->sequence(function ($sequence) {

            if ($sequence->index === 2) {
                return [
                    'meron' => 'red',
                    'wala' => 'blue'
                ];
            }
            return [];
        });
    }

}
