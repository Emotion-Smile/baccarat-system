<?php

namespace App\Kravanh\Domain\UserOption\Actions;

use App\Kravanh\Domain\UserOption\Support\Enum\Option;

class UserOptionHasUseSecondTraderAction
{
    public function __invoke(int $userId): bool
    {
        return (bool)app(UserOptionFindAction::class) ($userId, Option::USE_SECOND_TRADER);
    }
}
