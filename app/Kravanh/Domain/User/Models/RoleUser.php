<?php

namespace App\Kravanh\Domain\User\Models;

use App\Kravanh\Domain\User\Observers\RoleUserObserver;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleUser extends Pivot
{
    protected static function booted()
    {
        static::observe(RoleUserObserver::class);
    }
}
