<?php

namespace App\Kravanh\Domain\Integration\Nova\Http\Controllers\T88;

use App\Kravanh\Domain\Integration\Supports\Nova\T88GameConditionNovaActionFields;
use App\Models\User;

class GetGameConditionFieldController
{
    public function __invoke(User $user)
    {
        return asJson([
            'fields' => (new T88GameConditionNovaActionFields)($user)
        ]);
    }
}