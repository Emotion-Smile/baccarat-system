<?php

namespace App\Kravanh\Domain\User\Subscribers;

class CreateLoginHistory
{
    public function handle($event)
    {
        $event->user
            ->loginHistories()
            ->create([
                'ip' => request()->header('x-vapor-source-ip') ?? request()->ip(),
                'user_agent' => request()->header('User-Agent'),
                'login_at' => now()
            ]);
    }
}
