<?php

namespace App\Kravanh\Domain\User\Policies;

use App\Models\User;
use App\Models\User as UserModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubAccountPolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        return ($user->isRoot() || $user->hasPermission('SubAccount:full-manage')) ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('SubAccount:view-any');
    }

    public function view(User $user, UserModel $userRecord): bool
    {
        if ($userRecord->isRoot()) {
            return false;
        }

        if ($user->isOwner($userRecord->id)) {
            return true;
        }

        return $user->hasPermission('SubAccount:view');
    }


    public function create(User $user): bool
    {
        return $user->hasPermission('SubAccount:create');
    }


    public function update(User $user, UserModel $userRecord): bool
    {
        if ($userRecord->isRoot()) {
            return false;
        }

        if ($user->isOwner($userRecord->id)) {
            return true;
        }

        return $user->hasPermission('SubAccount:update');
    }


    public function delete(User $user, UserModel $userRecord): bool
    {
        if ($userRecord->isRoot()) {
            return false;
        }

        return $user->hasPermission('SubAccount:delete');
    }


    public function restore(User $user): bool
    {
        return $user->hasPermission('SubAccount:restore');
    }


    public function forceDelete(User $user): bool
    {
        return $user->hasPermission('SubAccount:force-delete');
    }


    public function uploadFiles(User $user): bool
    {
        return true;
    }
}
