<?php

namespace App\Kravanh\Domain\Game\Actions;

use App\Models\User;

final class UserGetSuperSeniorIdAction
{
    public function __invoke(int $userId): int
    {

        //@FixME fix later
        if ($userId === 0) {
            return 0;
        }

        $user = User::query()
            ->select([
                'super_senior',
            ])
            ->find($userId);

        return $user->super_senior;

    }
}
