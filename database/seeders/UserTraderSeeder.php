<?php

namespace Database\Seeders;

use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTraderSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'trader_seeder',
            'password' => Hash::make('password'),
            'type' => UserType::TRADER,
            'environment_id' => 1,
            'group_id' => 1
        ]);
    }
}
