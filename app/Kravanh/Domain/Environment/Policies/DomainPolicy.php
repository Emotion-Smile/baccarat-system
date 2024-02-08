<?php

namespace App\Kravanh\Domain\Environment\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DomainPolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        return $user->isRoot() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('Domain:view-any');
    }

    public function view(User $user): bool
    {
        return $user->hasPermission('Domain:view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('Domain:create');
    }


    public function update(User $user): bool
    {
        return $user->hasPermission('Domain:update');
    }


    public function delete(User $user): bool
    {

        return $user->hasPermission('Domain:delete');
    }

    public function restore(User $user): bool
    {
        return $user->hasPermission('Domain:restore');
    }


    public function forceDelete(User $user): bool
    {
        return $user->hasPermission('Domain:force-delete');
    }

}
