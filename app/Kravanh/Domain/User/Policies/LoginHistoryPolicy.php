<?php

namespace App\Kravanh\Domain\User\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoginHistoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('LoginHistory:view-any') || $user->isRoot();
    }

    public function view(User $user): bool
    {
        return $user->hasPermission('LoginHistory:view') || $user->isRoot();
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
