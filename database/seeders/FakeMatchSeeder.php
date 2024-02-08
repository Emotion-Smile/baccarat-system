<?php

namespace Database\Seeders;

use App\Kravanh\Domain\Match\Models\BetRecord;
use App\Kravanh\Domain\Match\Models\Matches;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class FakeMatchSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        BetRecord::truncate();
        Matches::truncate();
        Schema::enableForeignKeyConstraints();

        $totalMatch = 10;
        $totalBetPerMatch = 10;

        Matches::factory()
            ->count($totalMatch)
            ->has(BetRecord::factory()->state(new Sequence(
                ['bet_on' => 1],
                ['bet_on' => 2],
            ))->count($totalBetPerMatch))
            ->state(new Sequence(
                function ($sequence) use ($totalMatch) {

                    $result = $sequence->index % 2 ? 1 : 2;
                    $matchEndAt = now()->addMinutes($sequence->index + 2);
                    $bet_started = now();
                    $bet_ended = now()->addMinutes($sequence->index + 1);

                    if ($sequence->index === ($totalMatch - 1)) {
                        $result = 0;
                        $matchEndAt = null;
                        $bet_started = null;
                        $bet_ended = null;
                    }

                    return [
                        'result' => $result,
                        'fight_number' => $sequence->index + 1,
                        'match_started_at' => now()->addMinutes($sequence->index + 1),
                        'match_end_at' => $matchEndAt,
                        'bet_started_at' => $bet_started,
                        'bet_stopped_at' => $bet_ended
                    ];
                }
            ))->create();
    }
}
