<?php

namespace App\Kravanh\Domain\User\Policies;

use App\Models\User;
use App\Models\User as UserModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class AgentPolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        return ($user->isRoot() || $user->hasPermission('Agent:full-manage')) ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('Agent:view-any');
    }

    public function view(User $user, UserModel $userRecord): bool
    {
        if ($userRecord->isRoot()) {
            return false;
        }

        if ($user->isOwner($userRecord->id)) {
            return true;
        }

        return $user->hasPermission('Agent:view');
    }


    public function create(User $user): bool
    {
        return $user->hasPermission('Agent:create');
    }


    public function update(User $user, UserModel $userRecord): bool
    {
        if ($userRecord->isRoot()) {
            return false;
        }

        if ($user->isOwner($userRecord->id)) {
            return true;
        }

        if ($user->isCompany()) {
            return true;
        }
        
        return $user->hasPermission('Agent:update');
    }


    public function delete(User $user, UserModel $userRecord): bool
    {
        if ($userRecord->isRoot()) {
            return false;
        }

        return $user->hasPermission('Agent:delete');
    }


    public function restore(User $user): bool
    {
        return $user->hasPermission('Agent:restore');
    }


    public function forceDelete(User $user): bool
    {
        return $user->hasPermission('Agent:force-delete');
    }


    public function uploadFiles(User $user): bool
    {
        return true;
    }
}
