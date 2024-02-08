<?php

namespace App\Kravanh\Domain\User\Policies;

use App\Models\User;
use App\Models\User as UserModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemberPolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        return ($user->isRoot() || $user->hasPermission('Member:full-manage')) ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('Member:view-any');
    }

    public function view(User $user, UserModel $userRecord): bool
    {
        if ($userRecord->isRoot()) {
            return false;
        }

        return $user->hasPermission('Member:view');
    }


    public function create(User $user): bool
    {
        return $user->hasPermission('Member:create');
    }


    public function update(User $user, UserModel $userRecord): bool
    {
        if ($userRecord->isRoot()) {
            return false;
        }

        if ($user->isCompany()) {
            return true;
        }
        
        return $user->hasPermission('Member:update');
    }


    public function delete(User $user, UserModel $userRecord): bool
    {
        if ($userRecord->isRoot()) {
            return false;
        }

        return $user->hasPermission('Member:delete');
    }


    public function restore(User $user): bool
    {
        return $user->hasPermission('Member:restore');
    }


    public function forceDelete(User $user): bool
    {
        return $user->hasPermission('Member:force-delete');
    }


    public function uploadFiles(User $user): bool
    {
        return true;
    }

    public function allowStream(User $user): bool
    {
        return $user->hasPermission('Member:allow-stream');
    }
}
