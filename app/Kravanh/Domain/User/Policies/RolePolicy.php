<?php

namespace App\Kravanh\Domain\User\Policies;

use App\Kravanh\Domain\User\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;


    public function before(User $user): ?bool
    {
        return (
            $user->isRoot() ||
            $user->hasPermission('Role:full-manage')
        )
            ? true
            : null;
    }


    public function viewAny(User $user) : bool
    {
        return $user->hasPermission('Role:view-any');
    }


    public function view(User $user, Role $role) : bool
    {
        return $user->hasPermission('Role:view');
    }


    public function create(User $user) : bool
    {
        return $user->hasPermission('Role:create');
    }


    public function update(User $user, Role $role) : bool
    {
        return $user->hasPermission('Role:update');
    }


    public function delete(User $user, Role $role) : bool
    {
        return $user->hasPermission('role:delete');
    }


    public function restore(User $user, Role $role) : bool
    {
        return $user->hasPermission('Role:restore');
    }


    public function forceDelete(User $user, Role $role) : bool
    {
        return $user->hasPermission('Role:force-delete');
    }
}
