<?php

namespace App\Kravanh\Domain\DragonTiger\App\Nova\Http\Controllers;

use App\Kravanh\Domain\DragonTiger\App\Nova\Forms\ConditionForm;
use App\Models\User;

class GetGameConditionFieldController
{
    public function __invoke(User $user)
    {
        return asJson([
            'fields' => ConditionForm::make($user)->buildFields()
        ]);
    }
}