<?php

namespace Database\Seeders;

use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

class MatchSeeder extends Seeder
{

    public function run(): void
    {
        $trader = User::whereType(UserType::TRADER)->first();

        Matches::create([
            'user_id' => $trader->id,
            'environment_id' => $trader->environment_id,
            'fight_number' => Matches::nextFightNumber($trader->group_id),
            'match_date' => Date::today(),
            'payout_total' => 180,
            'payout_meron' => 90,
            'bet_started_at' => Date::now(),
//            'bet_stopped_at' => Date::now(),
            'bet_duration' => 30,
        ]);

    }
}
