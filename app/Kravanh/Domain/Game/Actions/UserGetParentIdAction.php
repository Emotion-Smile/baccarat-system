<?php

namespace App\Kravanh\Domain\Game\Actions;

use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;

final class UserGetParentIdAction
{
    public function __invoke(int $userId): int
    {

        //@FixME fix later
        if ($userId === 0) {
            return 0;
        }

        $user = User::query()
            ->select([
                'id',
                'agent',
                'master_agent',
                'senior',
                'super_senior',
                'type'
            ])
            ->find($userId);

        return match ($user->type->value) {
            UserType::MEMBER => $user->agent,
            UserType::AGENT => $user->master_agent,
            UserType::MASTER_AGENT => $user->senior,
            UserType::SENIOR => $user->super_senior,
            default => 0
        };

    }
}
