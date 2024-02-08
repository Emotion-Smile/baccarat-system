<?php

namespace Database\Seeders;

use App\Kravanh\Domain\User\Models\MemberType;
use Illuminate\Database\Seeder;

class MemberTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $defaultSpread = collect([
            [
                'name' => 'Normal',
            ],
            [
                'name' => 'VIP',
            ],
            [
                'name' => 'VIP1',
            ],
            [
                'name' => 'VIP2',
            ]
        ]);

        $defaultSpread
            ->each(fn($spread) => MemberType::updateOrCreate(['name' => $spread['name']], ['name' => $spread['name']]));
    }
}
