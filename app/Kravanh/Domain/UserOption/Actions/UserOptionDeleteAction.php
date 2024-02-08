<?php

namespace App\Kravanh\Domain\UserOption\Actions;

use App\Kravanh\Domain\UserOption\Models\UserOption;

class UserOptionDeleteAction
{
    public function __invoke(int $userId, string $option): bool
    {
        return (bool)UserOption::where('user_id', $userId)
            ->where('option', $option)
            ->delete();
    }

}
