<?php

namespace App\Kravanh\Domain\WalletBackup\Actions;

use App\Models\User;

final class GetUserBalanceAction
{
    public function __invoke(string $userType, int $id): int
    {
        /**
         * @var User $userType
         */
        return (new $userType())
            ->select('id')
            ->find($id)?->balanceInt ?? 0;
    }
}
