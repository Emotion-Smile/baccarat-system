<?php

namespace App\Kravanh\Domain\User\Policies;

use App\Models\User;
use App\Models\User as UserModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, UserModel $userRecord): bool
    {

        return true;
    }


    public function create(User $user): bool
    {
        return false;
    }


    public function update(User $user, UserModel $userRecord): bool
    {
        return false;
    }


    public function delete(User $user, UserModel $userRecord): bool
    {
        return false;
    }


    public function restore(User $user): bool
    {
        return false;
    }


    public function forceDelete(User $user): bool
    {
        return false;
    }


    public function uploadFiles(User $user): bool
    {
        return false;
    }

}
