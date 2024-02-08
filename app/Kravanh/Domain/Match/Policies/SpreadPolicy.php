<?php

namespace App\Kravanh\Domain\Match\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpreadPolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        return $user->isRoot() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('Spread:view-any');
    }

    public function view(User $user): bool
    {
        return $user->hasPermission('Spread:view');
    }

    
    public function create(User $user): bool
    {
        return $user->hasPermission('Spread:create');
    }


    public function update(User $user): bool
    {
        return $user->hasPermission('Spread:update');
    }


    public function delete(): bool
    {
        return false;
    }

}
