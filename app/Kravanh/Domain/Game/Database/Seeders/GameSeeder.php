<?php

namespace App\Kravanh\Domain\Game\Database\Seeders;

use App\Kravanh\Domain\Game\Models\Game;
use App\Kravanh\Domain\Game\Models\GameTable;
use App\Kravanh\Domain\Game\Supports\GameName;
use App\Kravanh\Domain\Game\Supports\GameType;
use Illuminate\Database\Seeder;

final class GameSeeder extends Seeder
{
    public function run(): void
    {
//        $game = Game::updateOrCreate(
//            ['name' => GameName::DragonTiger],
//            [
//                'label' => 'Dragon & Tiger',
//                'type' => GameType::Casino,
//            ],
//        );
//
//        GameTable::updateOrCreate(
//            ['game_id' => $game->id],
//            [
//                'label' => 'table 1',
//                'stream_url' => '#',
//                'active' => true,
//            ],
//        );
        $this->baccaratGame();
    }

    protected function baccaratGame(): void
    {
        $game = Game::updateOrCreate(
            ['name' => GameName::Baccarat],
            [
                'label' => 'Baccarat',
                'type' => GameType::Casino,
            ],
        );

        GameTable::updateOrCreate(
            ['game_id' => $game->id],
            [
                'label' => 'table 1',
                'stream_url' => '#',
                'active' => true,
            ],
        );
    }
}
