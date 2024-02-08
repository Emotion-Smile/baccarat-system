<?php

namespace App\Kravanh\Domain\User\Observers;


use App\Kravanh\Domain\User\Models\RoleUser;
use Illuminate\Support\Facades\Cache;

class RoleUserObserver
{
    public function saving(RoleUser $roleUser): void
    {
        $this->forgetPermissionsCache($roleUser);
    }

    public function deleting(RoleUser $roleUser): void
    {
        $this->forgetPermissionsCache($roleUser);
    }

    protected function forgetPermissionsCache(RoleUser $roleUser): void
    {
        Cache::forget("permissions_user_${roleUser['user_id']}");
    }
}

