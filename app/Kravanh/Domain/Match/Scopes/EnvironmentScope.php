<?php

namespace App\Kravanh\Domain\Match\Scopes;

use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class EnvironmentScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        if (app()->runningInConsole()) {
            return;
        }

        if (!in_array(user()->type->value, [UserType::COMPANY, UserType::DEVELOPER])) {
            $builder
                ->where('environment_id', request()->user()->environment_id);
        }
    }
}
