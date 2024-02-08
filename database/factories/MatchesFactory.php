<?php

namespace Database\Factories;

use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use App\Kravanh\Domain\User\Models\Trader;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Date;

class MatchesFactory extends Factory
{
    protected $model = Matches::class;

    public function definition(): array
    {
        $trader = Trader::factory()->create();

        return [
            'user_id' => $trader->id,
            'group_id' => $trader->group_id,
            'environment_id' => $trader->environment_id,
            'fight_number' => Matches::nextFightNumber($trader->group_id),
            'match_date' => Date::today(),
            'payout_total' => 180,
            'payout_meron' => 90,
            'bet_started_at' => Date::now(),
//            'bet_stopped_at' => Date::now()->addSeconds(40), //null
            'match_started_at' => Date::now(),
            'bet_duration' => 30,
            'result' => MatchResult::NONE,
//            'match_end_at' => Date::now()->addMinutes(2)
        ];
    }

//    public function configure(): MatchesFactory
//    {
////        return $this->afterMaking(function (Matches $matches) {
////            $matches->match_end_at = $matches->match_started_at->addMinutes(5);
////        })->afterCreating(function (Matches $matches) {
////            $match = Matches::orderByDesc('id')->first();
////            ray('make', $match);
////
////        });
//    }
}
