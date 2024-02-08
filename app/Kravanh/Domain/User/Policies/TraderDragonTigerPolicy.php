<?php

namespace App\Kravanh\Domain\User\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TraderDragonTigerPolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        return ($user->isRoot() || $user->hasPermission('TraderDragonTiger:full-manage')) ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('TraderDragonTiger:view-any');
    }

    public function view(User $user, User $userRecord): bool
    {
        if ($userRecord->isRoot()) {
            return false;
        }

        return $user->hasPermission('TraderDragonTiger:view');
    }


    public function create(User $user): bool
    {
        return $user->hasPermission('TraderDragonTiger:create');
    }


    public function update(User $user, User $userRecord): bool
    {
        if ($userRecord->isRoot()) {
            return false;
        }

        return $user->hasPermission('TraderDragonTiger:update');
    }


    public function delete(User $user, User $userRecord): bool
    {
        if ($userRecord->isRoot()) {
            return false;
        }

        return $user->hasPermission('TraderDragonTiger:delete');
    }


    public function restore(User $user): bool
    {
        return $user->hasPermission('TraderDragonTiger:restore');
    }


    public function forceDelete(User $user): bool
    {
        return $user->hasPermission('Trader:force-delete');
    }


    public function uploadFiles(User $user): bool
    {
        return true;
    }
}
