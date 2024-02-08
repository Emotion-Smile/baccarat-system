<?php

namespace App\Kravanh\Domain\User\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;


    public function before(User $user): ?bool
    {
        return $user->isRoot() ? true : null;
    }


    public function viewAny(User $user): bool
    {
        return $user->hasPermission('Permission:view-any');
    }


    public function view(User $user): bool
    {
        return $user->hasPermission('Permission:view');
    }

    public function create(): bool
    {
        return false;
    }

    public function update(): bool
    {
        return false;
    }


    public function delete(): bool
    {
        return false;
    }


    public function restore(): bool
    {
        return false;
    }


    public function forceDelete(): bool
    {
        return false;
    }
}
