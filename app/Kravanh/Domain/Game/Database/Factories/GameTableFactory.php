<?php

namespace App\Kravanh\Domain\Game\Database\Factories;

use App\Kravanh\Domain\Game\Models\Game;
use App\Kravanh\Domain\Game\Models\GameTable;
use App\Kravanh\Domain\Game\Supports\GameName;
use App\Kravanh\Domain\Game\Supports\GameType;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameTableFactory extends Factory
{
    protected $model = GameTable::class;

    public function definition(): array
    {
//        $game = Game::updateOrCreate(
//            ['name' => GameName::DragonTiger],
//            [
//                'label' => 'Dragon & Tiger',
//                'type' => GameType::Casino
//            ],
//        );
//        return [
//            'game_id' => $game->id,
//            'label' => $this->faker->name,
//            'stream_url' => '#',
//            'active' => true
//        ];
        return $this->baccaratGameTable();
    }

    protected function baccaratGameTable(): array
    {
        $game = Game::updateOrCreate(
            ['name' => GameName::Baccarat],
            [
                'label' => 'Baccarat',
                'type' => GameType::Casino
            ],
        );
        return [
            'game_id' => $game->id,
            'label' => $this->faker->name,
            'stream_url' => '#',
            'active' => true
        ];
    }
}
