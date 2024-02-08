<?php

namespace App\Kravanh\Domain\User\Policies;

use App\Models\User;
use App\Models\User as UserModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class TraderPolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        return ($user->isRoot()|| $user->hasPermission('Trader:full-manage')) ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return  $user->hasPermission('Trader:view-any');
    }

    public function view(User $user, UserModel $userRecord): bool
    {
        if ($userRecord->isRoot()) {
            return false;
        }

        return $user->hasPermission('Trader:view');
    }


    public function create(User $user): bool
    {
        return $user->hasPermission('Trader:create');
    }


    public function update(User $user, UserModel $userRecord): bool
    {
        if ($userRecord->isRoot()) {
            return false;
        }

        return $user->hasPermission('Trader:update');
    }


    public function delete(User $user, UserModel $userRecord): bool
    {
        if ($userRecord->isRoot()) {
            return false;
        }

        return $user->hasPermission('Trader:delete');
    }


    public function restore(User $user): bool
    {
        return $user->hasPermission('Trader:restore');
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
