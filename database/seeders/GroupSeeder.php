<?php

namespace Database\Seeders;

use App\Kravanh\Domain\Environment\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{

    public function run(): void
    {
        Group::factory()->count(4)->createQuietly();
    }
}
