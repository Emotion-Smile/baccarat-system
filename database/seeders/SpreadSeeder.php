<?php

namespace Database\Seeders;

use App\Kravanh\Domain\Match\Models\Spread;
use Illuminate\Database\Seeder;

class SpreadSeeder extends Seeder
{

    public function run(): void
    {
        $defaultSpread = collect([
            [
                "name" => "A",
                "payout_deduction" => 0
            ],
            [
                'name' => 'B',
                'payout_deduction' => 3
            ],
            [
                'name' => 'C',
                'payout_deduction' => 5
            ],
//            [
//                'name' => 'D',
//                'payout_deduction' => 8
//            ],
//            [
//                'name' => 'E',
//                'payout_deduction' => 10
//            ]
        ]);

        $defaultSpread
            ->each(fn($spread) => Spread::updateOrCreate(['name' => $spread['name']], ['payout_deduction' => $spread['payout_deduction']]));
    }
}
