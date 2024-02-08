<?php

namespace App\Kravanh\Domain\User\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemberTypePolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        return $user->isRoot() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('MemberType:view-any');
    }

    public function view(User $user): bool
    {
        return $user->hasPermission('MemberType:view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('MemberType:create');
    }


    public function update(User $user): bool
    {
        return $user->hasPermission('MemberType:update');
    }
    
    public function delete(User $user): bool
    {
        return $user->hasPermission('MemberType:delete');
    }

}
