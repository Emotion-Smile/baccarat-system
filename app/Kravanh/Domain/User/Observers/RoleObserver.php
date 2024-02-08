<?php

namespace App\Kravanh\Domain\User\Observers;

use App\Kravanh\Domain\User\Models\Role;
use Illuminate\Support\Facades\Cache;

class RoleObserver
{
    public function saving(Role $role): void
    {
        $role
            ->users()
            ->pluck('id')
            ->each(function ($userId) {
                Cache::forget("permissions_{$userId}");
            });
    }
}
