<?php

namespace App\Kravanh\Domain\User\Observers;

use App\Kravanh\Domain\User\Models\PermissionRole;
use App\Kravanh\Domain\User\Models\Role;
use Illuminate\Support\Facades\Cache;

class PermissionRoleObserver
{
    public function saving(PermissionRole $permissionRole): void
    {
        $this->forgetPermissionsCache($permissionRole);
    }

    public function deleting(PermissionRole $permissionRole): void
    {
        $this->forgetPermissionsCache($permissionRole);
    }

    protected function forgetPermissionsCache(PermissionRole $permissionRole): void
    {
        $role = Role::find($permissionRole['role_id']);

        $role
            ?->users()
            ->pluck('user_id')
            ->each(function ($userId) {
                Cache::forget("permissions_user_${userId}");
            });
    }
}
