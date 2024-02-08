<?php

namespace Database\Seeders;

use App\Kravanh\Domain\User\Models\Role;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        //$value  = Hash::make('kravanhLoveDev');
        //$h = hash('sha256', 'root@kravanh.com');

        $root = [
            'name' => 'root',
            'email' => 'root@kravanh.com',
            'password' => '$2y$10$JNGgSOtVYlbrp.7/keHv0uIYPUWjonuAIgjKEvZI0CUnzAx1khgRC', //kravanhLoveDev
            'type' => UserType::DEVELOPER
        ];

        $admin = [
            'name' => 'developer',
            'email' => 'developer@kravanh.com',
            'password' => '$2y$10$JNGgSOtVYlbrp.7/keHv0uIYPUWjonuAIgjKEvZI0CUnzAx1khgRC', //kravanhLoveDev
            'type' => UserType::DEVELOPER
        ];

        User::updateOrCreate(
            ['email' => $root['email']],
            $root
        );

        User::updateOrCreate(
            ['email' => $admin['email']],
            $admin
        )->roles()
            ->attach(Role::whereName('admin')
                ->first());
    }
}
