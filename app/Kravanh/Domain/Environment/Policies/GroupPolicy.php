<?php

namespace App\Kravanh\Domain\Environment\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        return $user->isRoot() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('Group:view-any');
    }

    public function view(User $user): bool
    {
        return $user->hasPermission('Group:view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('Group:create');
    }


    public function update(User $user): bool
    {
        return $user->hasPermission('Group:update');
    }


    public function delete(User $user): bool
    {

        return $user->hasPermission('Group:delete');
    }

    public function restore(User $user): bool
    {
        return $user->hasPermission('Group:restore');
    }


    public function forceDelete(User $user): bool
    {
        return $user->hasPermission('Group:force-delete');
    }

}
