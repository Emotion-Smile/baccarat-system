<?php

namespace App\Kravanh\Domain\Match\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MatchesPolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        return $user->isRoot() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('Match:view-any');
    }

    public function view(User $user): bool
    {
        return $user->hasPermission('Match:view');
    }

    public function updatePendingResult(User $user): bool
    {
        return $user->hasPermission('Match:update-pending-result');
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

}
