<?php

namespace App\Kravanh\Domain\User\Policies;

use App\Models\User;
use App\Models\User as UserModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class SeniorPolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        return ($user->isRoot() || $user->hasPermission('Senior:full-manage')) ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('Senior:view-any');
    }

    public function view(User $user, UserModel $userRecord): bool
    {
        if ($userRecord->isRoot()) {
            return false;
        }

        if ($user->isOwner($userRecord->id)) {
            return true;
        }

        return $user->hasPermission('Senior:view');
    }


    public function create(User $user): bool
    {
        return $user->hasPermission('Senior:create');
    }


    public function update(User $user, UserModel $userRecord): bool
    {
        if ($userRecord->isRoot()) {
            return false;
        }

        if ($user->isOwner($userRecord->id)) {
            return true;
        }

        return $user->hasPermission('Senior:update');
    }


    public function delete(User $user, UserModel $userRecord): bool
    {
        if ($userRecord->isRoot()) {
            return false;
        }

        return $user->hasPermission('Senior:delete');
    }


    public function restore(User $user): bool
    {
        return $user->hasPermission('Senior:restore');
    }


    public function forceDelete(User $user): bool
    {
        return $user->hasPermission('Senior:force-delete');
    }


    public function uploadFiles(User $user): bool
    {
        return true;
    }
}
