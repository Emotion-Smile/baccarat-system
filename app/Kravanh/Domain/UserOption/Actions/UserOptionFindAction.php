<?php

namespace App\Kravanh\Domain\UserOption\Actions;

use App\Kravanh\Domain\UserOption\Models\UserOption;

class UserOptionFindAction
{
    public function __invoke(int $userId, string $option)
    {
        return UserOption::query()
            ->where('user_id', $userId)
            ->where('option', $option)
            ->first(['value']);
    }
}
