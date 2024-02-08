<?php

namespace App\Kravanh\Domain\User\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActionEventPolicy
{
    use HandlesAuthorization;

    public function before(User $user) : ?bool
    {
        return $user->isRoot() ? true : null;
    }

    public function viewAny(User $user) : bool
    {
        return $user->isRoot();
    }


    public function view(User $user) : bool
    {
        return $user->isRoot();
    }
}
