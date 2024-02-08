<?php

namespace App\Kravanh\Domain\Environment\Policies;

use App\Kravanh\Domain\Environment\Models\Environment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EnvironmentPolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        return $user->isRoot() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('Environment:view-any');
    }

    public function view(User $user): bool
    {
        return $user->hasPermission('Environment:view');
    }


    public function create(User $user): bool
    {
        return $user->hasPermission('Environment:create');
    }


    public function update(User $user): bool
    {
        return $user->hasPermission('Environment:update');
    }


    public function delete(User $user, Environment $environment): bool
    {
        if ($environment->hasUser()) {
            return false;
        }

        return $user->hasPermission('Environment:delete');
    }

    public function restore(User $user): bool
    {
        return $user->hasPermission('Environment:restore');
    }


    public function forceDelete(User $user): bool
    {
        return $user->hasPermission('Environment:force-delete');
    }

}
