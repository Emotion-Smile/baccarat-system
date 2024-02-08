<?php

namespace App\Kravanh\Domain\User\Supports\Traits;

use App\Kravanh\Domain\User\Models\Role;
use App\Kravanh\Domain\User\Models\RoleUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;

/**@mixin User**/
trait HasRoles
{
    public function isRoot(): bool
    {
        return $this->name == 'root';
    }

    public function roles(): BelongsToMany
    {
        return $this
            ->belongsToMany(Role::class, RoleUser::class, 'user_id', 'role_id')
            ->using(RoleUser::class);
    }

    public function isOwner($userId): bool
    {
        return $this->id === $userId;
    }

    public function assignRole($role): Model
    {
        if (is_string($role)) {
            return $this->roles()->save(
                Role::whereName($role)->firstOrFail()
            );
        }

        return $this->roles()->save($role);
    }

    public function hasRole($role): bool
    {

        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return !!$role->intersect($this->roles)->count();
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->getPermissions());
    }

    public function getPermissions(): array
    {
        $userPermissions = [];

        $allPermissions = Cache::remember(
            "permissions_user_{$this->id}",
            Date::now()->addMinute(),
            function () {
                return $this->roles()
                    ->with('permissions:name')
                    ->get()
                    ->map(fn ($role) => $role->permissions)
                    ->toArray();
            }
        );

        foreach ($allPermissions as $permissions) {
            foreach ($permissions as $permission) {
                $userPermissions[] = $permission['name'];
            }
        }

        return $userPermissions;
    }
}
