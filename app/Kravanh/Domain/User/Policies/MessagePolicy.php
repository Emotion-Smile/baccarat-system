<?php

namespace App\Kravanh\Domain\User\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        return ($user->isRoot() || $user->hasPermission('Message:full-manage')) ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('Message:view-any');
    }

    public function view(User $user): bool
    {
        return $user->hasPermission('Message:view');
    }


    public function create(User $user): bool
    {
        return $user->hasPermission('Message:create');
    }


    public function update(User $user): bool
    {
        return $user->hasPermission('Message:update');
    }


    public function delete(User $user): bool
    {
        return $user->hasPermission('Message:delete');
    }


    public function restore(User $user): bool
    {
        return $user->hasPermission('Message:restore');
    }


    public function forceDelete(User $user): bool
    {
        return $user->hasPermission('Message:force-delete');
    }
}
