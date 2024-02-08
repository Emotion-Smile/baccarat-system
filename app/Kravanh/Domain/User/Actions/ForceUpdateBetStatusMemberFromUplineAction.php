<?php

namespace App\Kravanh\Domain\User\Actions;

use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;

class ForceUpdateBetStatusMemberFromUplineAction
{
    public function __invoke(User $user, $status): void
    {
        if ($this->exceptUser($user)) return;

        User::where($user->type, $user->id)
            ->where('type', UserType::MEMBER)
            ->update([
                'status' => $status
            ]); 
    }

    protected function exceptUser(User $user): bool 
    {
        return in_array($user->type, [
            UserType::COMPANY,
            UserType::TRADER,
            UserType::DEVELOPER,
            UserType::SUB_ACCOUNT,
            UserType::REPORTER,
            UserType::MEMBER
        ]);
    }
}
