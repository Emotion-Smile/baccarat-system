<?php

namespace Database\Seeders;

use App\Kravanh\Domain\Environment\Models\Environment;
use Illuminate\Database\Seeder;

class EnvironmentSeeder extends Seeder
{

    public function run(): void
    {
        Environment::updateOrCreate([
            'name' => 'test',
            'domain' => 'localhost'
        ], ['name' => 'test']);
    }
}
