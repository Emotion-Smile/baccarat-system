<?php

namespace Database\Seeders;

use App\Kravanh\Domain\Match\Models\BetRecord;
use App\Kravanh\Domain\Match\Supports\Enums\BetOn;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class BetRecordSeeder extends Seeder
{

    public function run(): void
    {
        $this->call(MatchSeeder::class);

        User::whereType(UserType::MEMBER)->first()->transactions()->delete();

        BetRecord::truncate();

        BetRecord::factory()
            ->count(100)
            ->state(new Sequence(
                ['bet_on' => BetOn::WALA],
                ['bet_on' => BetOn::MERON]
            ))->create();
    }
}
