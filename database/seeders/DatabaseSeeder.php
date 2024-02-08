<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(
            [
                EnvironmentSeeder::class,
                PermissionSeeder::class,
                RoleSeeder::class,
                GroupSeeder::class,
                UserSeeder::class,
                UserTraderSeeder::class,
                SpreadSeeder::class
            ]
        );

        //$this->callWith(UserTestSeeder::class, ['currency' => Currency::USD]);
    }
}
