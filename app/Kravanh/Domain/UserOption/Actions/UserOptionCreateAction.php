<?php

namespace App\Kravanh\Domain\UserOption\Actions;

use App\Kravanh\Domain\UserOption\Models\UserOption;

class UserOptionCreateAction
{
    public function __invoke(int $userId, string $option)
    {
        return UserOption::create([
            'user_id' => $userId,
            'option' => $option
        ]);
    }
}
